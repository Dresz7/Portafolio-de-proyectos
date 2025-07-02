import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { UserScreen } from "../screens/UserScreen";
import { screen } from "../utils";

const Stack = createNativeStackNavigator();

export function UserStack() {
  return (
    // Cada stack contiene las screens
    <Stack.Navigator>
      <Stack.Screen
        name={screen.user.user}
        component={UserScreen}
        options={{ title: "Cuenta", headerTitleAlign: "center" }}
      />
    </Stack.Navigator>
  );
}
