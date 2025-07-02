import React, { useState } from "react";
import { View } from "react-native";
import { Avatar, Text } from "@rneui/themed";
import * as ImagePicker from "expo-image-picker";
import { getAuth, updateProfile } from "firebase/auth";
import { getStorage, ref, uploadBytes, getDownloadURL } from "firebase/storage";
import { styles } from "./InfoUser.styles";

export function InfoUser(props) {
  const { setLoading, setLoadingText } = props;
  const { uid, photoURL, displayName, email } = getAuth().currentUser;
  const [avatar, setAvatar] = useState(photoURL);

  // Función para cambiar avatar
  const changeAvatar = async () => {
    // Lanzar el selector de imágenes
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      allowsEditing: true,
      aspect: [4, 3],
    });

    // Si no se canceló, subir la imagen seleccionada
    if (!result.canceled) uploadImage(result.assets[0].uri);
  };

  // Función para subir la imagen seleccionada
  const uploadImage = async (uri) => {
    setLoadingText("Actualizando avatar");
    setLoading(true);

    // Obtener la imagen como blob
    const response = await fetch(uri);
    const blob = await response.blob();

    // Referencia a Firebase Storage
    const storage = getStorage();
    const storageRef = ref(storage, `avatar/${uid}`);

    // Subir el blob a la referencia especificada
    uploadBytes(storageRef, blob).then((snapshot) => {
      // Actualizar URL de foto después de subir
      updatePhotoUrl(snapshot.metadata.fullPath);
    });
  };

  // Función para actualizar URL de la foto del usuario
  const updatePhotoUrl = async (imagePath) => {
    // Obtener URL de descarga de la imagen
    const storage = getStorage();
    const imageRef = ref(storage, imagePath);
    const imageUrl = await getDownloadURL(imageRef);

    // Actualizar perfil del usuario con la nueva URL de foto
    const auth = getAuth();
    updateProfile(auth.currentUser, { photoURL: imageUrl });
    setAvatar(imageUrl);

    setLoading(false);
  };

  return (
    <View style={styles.content}>
      <Avatar
        size="large"
        rounded
        containerStyle={styles.avatar}
        icon={avatar ? null : { type: "material", name: "person" }}
        source={avatar ? { uri: avatar } : null}
      >
        <Avatar.Accessory size={24} onPress={changeAvatar} />
      </Avatar>
      <View>
        <Text style={styles.displayName}>{displayName || "Anonimo"}</Text>

        <Text>{email}</Text>
      </View>
    </View>
  );
}
