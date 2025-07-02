import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { LoginScreen } from "../screens/LoginScreen";
import { screen } from "../utils";

const Stack = createNativeStackNavigator();

export function LoginStack() {
  return (
    // Cada stack contiene las screens
    <Stack.Navigator>
      <Stack.Screen
        name={screen.login.login}
        component={LoginScreen}
        options={{ title: "Iniciar sesion", headerTitleAlign: "center" }}
      />
    </Stack.Navigator>
  );
}
