import React, { useCallback } from "react";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { useFocusEffect, CommonActions } from "@react-navigation/native";
import {
  RegisterOutScreen,
  QrScannedOutScreen,
  QrScannerOutScreen,
} from "../screens/RegisterOutScreen";
import { screen } from "../utils";

// Funcion que detecta en que screen se encuentra el usuario
export function RegisterOutStack({ navigation }) {
  useFocusEffect(
    useCallback(() => {
      // Obtener el estado de navegación
      const state = navigation.getState();

      // Verificar que state y state.routes no sean undefined
      if (state && state.routes) {
        // Buscar el stack anidado por su nombre
        const stackState = state.routes.find(
          (route) => route.name === screen.registerOut.tab
        );

        // Verificar que stackState y stackState.state no sean undefineda
        if (stackState && stackState.state) {
          // Obtener la ruta activa actual dentro del stack anidado
          const activeRouteName =
            stackState.state.routes[stackState.state.index].name;

          // Si no estás ya en screen.registerOut.registerOut, reinicia el stack
          if (activeRouteName !== screen.registerOut.registerOut) {
            navigation.dispatch(
              CommonActions.reset({
                index: 0,
                routes: [{ name: screen.registerOut.registerOut }],
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
    <Stack.Navigator>
      <Stack.Screen
        name={screen.registerOut.registerOut}
        component={RegisterOutScreen}
        options={{ title: "Registro de salidas", headerTitleAlign: "center" }}
      />
      <Stack.Screen
        name={screen.registerOut.qrScannerOut}
        component={QrScannerOutScreen}
        options={{ title: "Escáner Qr", headerTitleAlign: "center" }}
      />
      <Stack.Screen
        name={screen.registerOut.qrScannedOut}
        component={QrScannedOutScreen}
        options={{ title: "Qr escaneado", headerTitleAlign: "center" }}
      />
    </Stack.Navigator>
  );
}
