import React, { useState, useEffect } from "react";
import { View, ScrollView, Text, StyleSheet, Alert } from "react-native";
import { Card, ListItem, Button } from "@rneui/themed";
import { useNavigation } from "@react-navigation/native";
import { LoadingModal } from "../../../components/Shared";
import { screen } from "../../../utils";
import { styles } from "./QrScannedInScreen.style";

export function QrScannedInScreen({ route }) {
  const navigation = useNavigation();
  // Valor del qr que se envio
  const { scannedCode } = route.params;
  const [vehiculo, setVehiculo] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    inter(scannedCode);
  }, []);

  const inter = async (id) => {
    try {
      setLoading(true);
      await ConsultarDatos(id);
      setLoading(false);
    } catch (error) {
      console.error("Error cargandp QRCode: ", error);
    }
  };

  // Funcion que manda la solicitud a la api
  async function ConsultarDatos(id) {
    try {
      //URL
      const url = `http://72.167.55.139:8080/ExpoApi/Register/ConsultaVehiculo.php?id=${id}`;

      //Solicitud HTTP usando fetch
      const response = await fetch(url);

      // Verifica si la respuesta fue exitosa
      if (!response.ok) {
        throw new Error("La solicitud no pudo ser completada.");
      }

      // Obtiene los datos de la respuesta
      const data = await response.json();
      // console.log("Datos recibidos:", data);

      // Actualiza el estado del vehículo con los datos obtenidos
      setVehiculo(data[0]);
    } catch (error) {
      console.error("Error al obtener datos:", error);
      // Arreglo por defecto en caso de error
      const vehiculoObtenido = {
        id: 1,
        marca: "Marca1",
        linea: "Linea1",
        modelo: "Modelo1",
        color: "Color1",
        transmision: "Transmision1",
        serie: "Serie1",
        placa: "Placa1",
        cilindros: "Cilindros1",
        capacidadCarga: "CapacidadCarga1",
      };
      setVehiculo(vehiculoObtenido);
    }
  }

  // Funcion cuando se presiona confirmar
  const handleConfirmar = async () => {
    try {
      setLoading(true);
      const formData = new FormData();
      formData.append("serie", vehiculo.serie);
      formData.append("marca", vehiculo.marca);
      formData.append("linea", vehiculo.linea);
      formData.append("modelo", vehiculo.modelo);
      formData.append("color", vehiculo.color);
      formData.append("placa", vehiculo.placa);

      const response = await fetch(
        "http://72.167.55.139:8080/ExpoApi/Register/RegisterIn/UpdateRegister.php",
        {
          method: "POST",
          body: formData,
        }
      );

      if (!response.ok) {
        throw new Error("No se pudo completar la solicitud.");
      }

      const responseData = await response.json();

      if (responseData.success) {
        Alert.alert("Confirmación", responseData.success, [
          {
            text: "OK",
            onPress: () => {
              navigation.navigate(screen.registerIn.tab, {
                screen: screen.registerIn.registerIn,
              });
            },
          },
        ]);
      } else {
        Alert.alert("Error", responseData.error);
      }
    } catch (error) {
      console.error("Error durante la inserción:", error);
      Alert.alert("Error", error.message, [
        {
          text: "OK",
          onPress: () => {
            navigation.navigate(screen.registerIn.tab, {
              screen: screen.registerIn.registerIn,
            });
          },
        },
      ]);
    } finally {
      setLoading(false);
    }
  };

  // Funcion cuando se presiona cancelar
  const handleCancelar = () => {
    navigation.navigate(screen.registerIn.tab, {
      screen: screen.registerIn.registerIn,
    });
  };

  return (
    <>
      <ScrollView contentContainerStyle={styles.container}>
        {vehiculo && (
          <Card containerStyle={styles.card}>
            <Card.Title style={styles.cardTitle}>
              Detalles del Vehículo
            </Card.Title>
            <View style={styles.detailsContainer}>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>ID:</Text>
                  <Text>{vehiculo.id}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Marca:</Text>
                  <Text>{vehiculo.marca}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Línea:</Text>
                  <Text>{vehiculo.linea}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Modelo:</Text>
                  <Text>{vehiculo.modelo}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Color:</Text>
                  <Text>{vehiculo.color}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Transmisión:</Text>
                  <Text>{vehiculo.transmision}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Serie:</Text>
                  <Text>{vehiculo.serie}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Placa:</Text>
                  <Text>{vehiculo.placa}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem bottomDivider>
                <ListItem.Content>
                  <Text style={styles.label}>Cilindros:</Text>
                  <Text>{vehiculo.cilindros}</Text>
                </ListItem.Content>
              </ListItem>
              <ListItem>
                <ListItem.Content>
                  <Text style={styles.label}>Capacidad de Carga:</Text>
                  <Text>{vehiculo.capacidadCarga}</Text>
                </ListItem.Content>
              </ListItem>
            </View>
            <View style={styles.buttonContainer}>
              <Button
                title="Confirmar"
                color="#00a680"
                onPress={handleConfirmar}
                style={styles.button}
              />
              <Button
                title="Cancelar"
                color="#ff597f"
                onPress={handleCancelar}
                style={styles.button}
              />
            </View>
          </Card>
        )}
      </ScrollView>

      <LoadingModal show={loading} text={"Cargando datos"} />
    </>
  );
}
