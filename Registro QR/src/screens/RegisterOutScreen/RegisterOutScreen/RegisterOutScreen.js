import React from "react";
import { View, Text } from "react-native";
import { Button } from "@rneui/themed";
import { screen } from "../../../utils";
import { styles } from "./RegisterOutScreen.style";

export function RegisterOutScreen(props) {
  const { navigation } = props;
  // Funcion que manda al screen de la camara
  const goToQrScannerOut = () => {
    navigation.navigate(screen.registerOut.tab, {
      screen: screen.registerOut.qrScannerOut,
    });
  };

  return (
    <View>
      <Text>Estamos en la screen de registro de salidas</Text>

      <Button title="Escánear salida" onPress={goToQrScannerOut} />
    </View>
  );
}
