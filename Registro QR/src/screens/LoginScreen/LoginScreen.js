import React from "react";
import { View, ScrollView } from "react-native";
import { Text } from "@rneui/themed";
import { LoginForm } from "../../components/Auth";
import { styles } from "./LoginScreen.styles";

export function LoginScreen() {
  return (
    <ScrollView>
      <View style={styles.content}>
        {/* Carga el Login Form */}
        <LoginForm />
      </View>
    </ScrollView>
  );
}
