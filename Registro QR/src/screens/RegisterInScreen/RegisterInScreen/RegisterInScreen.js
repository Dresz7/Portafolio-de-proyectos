import React from "react";
import { View, Text } from "react-native";
import { Button } from "@rneui/themed";
import { screen } from "../../../utils";
import { styles } from "./RegisterInScreen.style";

export function RegisterInScreen(props) {
  const { navigation } = props;
  // Funcion que manda al screen de la camara
  const goToQrScannerIn = () => {
    navigation.navigate(screen.registerIn.tab, {
      screen: screen.registerIn.qrScannerIn,
    });
  };

  return (
    <View>
      <Text>Estamos en la screen de registro de entradas</Text>

      <Button title="Escánear entrada" onPress={goToQrScannerIn} />
    </View>
  );
}
