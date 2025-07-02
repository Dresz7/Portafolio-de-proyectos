import React, { useCallback } from "react";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { useFocusEffect, CommonActions } from "@react-navigation/native";
import {
  QrScannedInScreen,
  QrScannerInScreen,
  RegisterInScreen,
} from "../screens/RegisterInScreen";
import { screen } from "../utils";

// Funcion que detecta en que screen se encuentra el usuario
export function RegisterInStack({ navigation }) {
  useFocusEffect(
    useCallback(() => {
      // Obtener el estado de navegación
      const state = navigation.getState();

      // Verificar que state y state.routes no sean undefined
      if (state && state.routes) {
        // Buscar el stack anidado por su nombre
        const stackState = state.routes.find(
          (route) => route.name === screen.registerIn.tab
        );

        // Verificar que stackState y stackState.state no sean undefined
        if (stackState && stackState.state) {
          // Obtener la ruta activa actual dentro del stack anidado
          const activeRouteName =
            stackState.state.routes[stackState.state.index].name;

          // Si no estás ya en screen.registerIn.registerIn, reinicia el stack
          if (activeRouteName !== screen.registerIn.registerIn) {
            navigation.dispatch(
              CommonActions.reset({
                index: 0,
                routes: [{ name: screen.registerIn.registerIn }],
              })
            );
          }
        }
      }
      return () => {};
    }, [navigation])
  );

  const Stack = createNativeStackNavigator();

  return (
    // Cada stack contiene las screens
    <Stack.Navigator initialRouteName={screen.registerIn.registerIn}>
      <Stack.Screen
        name={screen.registerIn.registerIn}
        component={RegisterInScreen}
        options={{ title: "Registro de entradas", headerTitleAlign: "center" }}
      />
      <Stack.Screen
        name={screen.registerIn.qrScannerIn}
        component={QrScannerInScreen}
        options={{ title: "Escáner Qr", headerTitleAlign: "center" }}
      />
      <Stack.Screen
        name={screen.registerIn.qrScannedIn}
        component={QrScannedInScreen}
        options={{ title: "Qr escaneado", headerTitleAlign: "center" }}
      />
    </Stack.Navigator>
  );
}
