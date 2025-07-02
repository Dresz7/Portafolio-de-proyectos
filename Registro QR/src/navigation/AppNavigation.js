import { createBottomTabNavigator } from "@react-navigation/bottom-tabs";
import { Icon } from "@rneui/themed";
import { RegisterOutStack } from "./RegisterOutStack";
import { RegisterInStack } from "./RegisterInStack";
import { UserStack } from "./UserStack";
import { QrGeneratorStack } from "./QrGeneratorStack";
import { screen } from "../utils";

const Tab = createBottomTabNavigator();

export function AppNavigation() {
  return (
    // Tab que genera las distintos botones del navegador.
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarActiveTintColor: "#00a680",
        tabBarInactiveTintColor: "#646464",
        tabBarIcon: ({ color, size }) => tabBarIconOptions(route, color, size),
      })}
    >
      {/* Cada Tab contiene un stack */}
      <Tab.Screen
        name={screen.registerOut.tab}
        component={RegisterOutStack}
        options={{ title: "Registro de salidas", headerTitleAlign: "center" }}
      />
      <Tab.Screen
        name={screen.registerIn.tab}
        component={RegisterInStack}
        options={{ title: "Registro de entradas", headerTitleAlign: "center" }}
      />
      <Tab.Screen
        name={screen.qrGenerator.tab}
        component={QrGeneratorStack}
        options={{ title: "Generador de Qr", headerTitleAlign: "center" }}
      />
      <Tab.Screen
        name={screen.user.tab}
        component={UserStack}
        options={{ title: "Cuenta", headerTitleAlign: "center" }}
      />
    </Tab.Navigator>
  );
}

// Funcion que devuelve el icono de cada Tab
function tabBarIconOptions(route, color, size) {
  let iconName;

  if (route.name === screen.registerOut.tab) {
    iconName = "logout";
  }

  if (route.name === screen.registerIn.tab) {
    iconName = "login";
  }
  if (route.name === screen.user.tab) {
    iconName = "home-outline";
  }
  if (route.name === screen.qrGenerator.tab) {
    iconName = "qrcode";
  }

  return (
    <Icon type="material-community" name={iconName} color={color} size={size} />
  );
}
