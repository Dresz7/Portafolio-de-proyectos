import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { QrGeneratorScreen } from "../screens/QrGeneratorScreen";
import { screen } from "../utils";

const Stack = createNativeStackNavigator();

export function QrGeneratorStack() {
  return (
    // Cada stack contiene las screens
    <Stack.Navigator>
      <Stack.Screen
        name={screen.qrGenerator.qrGenerator}
        component={QrGeneratorScreen}
        options={{ title: "Generador de Qr", headerTitleAlign: "center" }}
      />
    </Stack.Navigator>
  );
}
