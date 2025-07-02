import React, { useState, useRef } from "react";
import { View, Alert } from "react-native";
import { Input, Button } from "@rneui/themed";
import QRCode from "react-native-qrcode-svg";
import { useFormik } from "formik";
import * as FileSystem from "expo-file-system";
import { StorageAccessFramework } from "expo-file-system";
import * as MediaLibrary from "expo-media-library";
import { PDFDocument, PageSizes } from "pdf-lib";
import { fromByteArray } from "react-native-quick-base64";
import { getStorage, ref, uploadString } from "firebase/storage";
import { decode } from "base-64";
import { LoadingModal } from "../../components";
import { initailvalues, validationSchema } from "./QrGenerator.data";
import { styles } from "./QrGeneratorScreen.style";

if (typeof atob === "undefined") {
  global.atob = decode;
}

export function QrGeneratorScreen() {
  const [loading, setLoading] = useState(false);
  const [qrValue, setQrValue] = useState("Bienvenido al generador de Qr");
  const svgRef = useRef(null);

  // Funcion que hace funcionar el Form
  const formik = useFormik({
    initialValues: initailvalues(),
    validationSchema: validationSchema(),
    validateOnChange: false,
    onSubmit: (formValue) => {
      setQrValue(formValue.qrValue);
    },
  });

  // Funcion que rebice el base64 y lo envia a guardar
  const saveQRCode = async () => {
    try {
      if (svgRef.current) {
        svgRef.current.toDataURL(async (dataURL) => {
          setLoading(true);
          await saveImage(dataURL);
          setLoading(false);
          Alert.alert("QRCode guardado exitosamente!");
        });
      } else {
        throw new Error("QRCode ref is not set.");
      }
    } catch (error) {
      console.error("Error guardando QRCode: ", error);
    }
  };

  // Funcion que guarda el qr
  const saveImage = async (dataURL) => {
    // En esta parte se guarda el qr como png
    try {
      const fileUri = FileSystem.cacheDirectory + `qrcode_${qrValue}.png`;
      await FileSystem.writeAsStringAsync(
        fileUri,
        dataURL.replace("data:image/png;base64,", ""),
        {
          encoding: FileSystem.EncodingType.Base64,
        }
      );

      await MediaLibrary.saveToLibraryAsync(fileUri);
      const pngImageBytes = await fetch(fileUri).then((res) =>
        res.arrayBuffer()
      );

      // En esta parte se guarda el base64 en firebase
      const storage = getStorage();
      const storageRef = ref(storage, `qrcodes/${qrValue}`);

      uploadString(storageRef, dataURL, "base64").then((snapshot) => {
        // console.log("Uploaded a base64 string!");
      });

      // En esta parte se genera el pdf
      const pdfDoc = await PDFDocument.create();
      const page = pdfDoc.addPage(PageSizes.Letter);
      const pngImage = await pdfDoc.embedPng(pngImageBytes);
      const pngDims = pngImage.scale(0.5);
      // Se agrega la imagen del qr al pdf
      page.drawImage(pngImage, {
        x: page.getWidth() / 2 - pngDims.width / 2,
        y: page.getHeight() / 2 - pngDims.height / 2,
        width: pngDims.width,
        height: pngDims.height,
      });
      const pdfBytes = await pdfDoc.save();

      const pdfBase64String = fromByteArray(pdfBytes);

      const permissions =
        await StorageAccessFramework.requestDirectoryPermissionsAsync();
      if (!permissions.granted) {
        return;
      }

      // En esta parte se guarda el pdf
      try {
        await StorageAccessFramework.createFileAsync(
          permissions.directoryUri,
          `qrcode_${qrValue}`,
          "application/pdf"
        )
          .then(async (uri) => {
            await FileSystem.writeAsStringAsync(uri, pdfBase64String, {
              encoding: FileSystem.EncodingType.Base64,
            });
          })
          .catch((e) => {
            console.log(e);
          });
      } catch (e) {
        throw new Error(e);
      }
    } catch (error) {
      console.error("Error saving QRCode as image: ", error);
    }
  };

  return (
    <View style={styles.container}>
      <QRCode
        size={308}
        quietZone={10}
        value={qrValue}
        getRef={(ref) => (svgRef.current = ref)}
      />
      <Input
        placeholder="Valor del Qr"
        onChangeText={(text) => formik.setFieldValue("qrValue", text)}
        errorMessage={formik.errors.qrValue}
      />
      <Button
        title="Cambiar valor"
        onPress={formik.handleSubmit}
        containerStyle={styles.input}
      />
      <Button
        title="Guardar Qr"
        onPress={saveQRCode}
        containerStyle={styles.input}
      />

      <LoadingModal show={loading} text={"Guardando"} />
    </View>
  );
}
