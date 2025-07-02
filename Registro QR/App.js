import { GestureHandlerRootView } from "react-native-gesture-handler";
import { LogBox } from "react-native";
import { NavigationContainer } from "@react-navigation/native";
import Toast from "react-native-toast-message";
import { LoginDivider } from "./src/navigation/LoginDivider";
import { firebaseApp, auth } from "./src/utils";

LogBox.ignoreAllLogs();

export default function App() {
  return (
    <GestureHandlerRootView style={{ flex: 1 }}>
      <NavigationContainer>
        <LoginDivider />
      </NavigationContainer>

      <Toast />
    </GestureHandlerRootView>
  );
}
