import { initializeApp } from "firebase/app";
import { initializeAuth, getReactNativePersistence } from "firebase/auth";
import ReactNativeAsyncStorage from "@react-native-async-storage/async-storage";

const firebaseConfig = {
  apiKey: "AIzaSyCHh0hbsDmTR9snDHAd148nwH4cIXuMnhM",
  authDomain: "register-app-test-2294b.firebaseapp.com",
  projectId: "register-app-test-2294b",
  storageBucket: "register-app-test-2294b.appspot.com",
  messagingSenderId: "875169170844",
  appId: "1:875169170844:web:9b797b55d7f8c14397f459",
};

// Inicializar Firebase
export const firebaseApp = initializeApp(firebaseConfig);

// Inicializar Auth con AsyncStorage
export const auth = initializeAuth(firebaseApp, {
  persistence: getReactNativePersistence(ReactNativeAsyncStorage),
});
