import React, { useEffect, useState, useRef, useCallback } from "react";
import {
  View,
  Text,
  StyleSheet,
  ActivityIndicator,
  Alert,
  AppState,
} from "react-native";
import { Slider } from "@rneui/themed";
import {
  useCameraPermission,
  useCameraDevice,
  Camera,
  useCodeScanner,
} from "react-native-vision-camera";
import { Gesture, GestureDetector } from "react-native-gesture-handler";
import Animated, {
  useSharedValue,
  withTiming,
  withDelay,
  runOnJS,
  useDerivedValue,
  interpolate,
  useAnimatedProps,
} from "react-native-reanimated";
import { useIsFocused } from "@react-navigation/native";
import { set } from "lodash";
import { screen } from "../../../utils";
import { styles } from "./QrScannerInScreen.style";

export function QrScannerInScreen(props) {
  const { navigation } = props;
  // Funcion que detecta el valor de Foreground
  const useIsForeground = () => {
    const [isForeground, setIsForeground] = useState(true);

    useEffect(() => {
      const onChange = (state) => {
        setIsForeground(state === "active");
      };
      const listener = AppState.addEventListener("change", onChange);
      return () => listener.remove();
    }, [setIsForeground]);

    return isForeground;
  };

  const { hasPermission, requestPermission } = useCameraPermission();
  const [activate, setActivate] = useState(true);
  const camera = useRef < Camera > null;
  const device = useCameraDevice("back");
  const isFocussed = useIsFocused();
  const isForeground = useIsForeground();
  const isActive = isFocussed && isForeground && activate;
  const [focusIconPosition, setFocusIconPosition] = useState({ x: 0, y: 0 });
  const focusIconOpacity = useSharedValue(0);
  const ReanimatedCamera = Animated.createAnimatedComponent(Camera);
  const exposureSlider = useSharedValue(0);

  // Funcion que cambia el brillo
  const exposureValue = useDerivedValue(() => {
    if (device == null) return 0;

    return interpolate(
      exposureSlider.value,
      [-1, 0, 1],
      [device.minExposure, 0, device.maxExposure]
    );
  }, [exposureSlider, device]);

  // Agrefa la animacion al cambio de brillo.
  const animatedProps = useAnimatedProps(() => ({
    exposure: exposureValue.value,
  }));

  // Funcion que hacer el enfoque
  const focus = useCallback((point) => {
    const c = camera.current;
    if (c == null) return;
    setFocusIconPosition(point);
    c.focus(point);
  }, []);

  // Detecta el gesto de hacer clic en la pantalla y llama a enfocar
  const gesture = Gesture.Tap().onEnd(({ x, y }) => {
    runOnJS(focus)({ x, y });
    focusIconOpacity.value = withTiming(1, { duration: 300 }, () => {
      focusIconOpacity.value = withDelay(1000, withTiming(0));
    });
  });

  // Funcion que recibe el valor del qr y lo envia a la screen qrscanned
  const handleCodeScanned = useCallback(async (codes) => {
    const scannedCode = codes[0].value.trim();
    navigation.navigate(screen.registerIn.qrScannedIn, { scannedCode });
  }, []);

  // Funcion que recibe configura el scaner de la camara
  const codeScanner = useCodeScanner({
    codeTypes: ["qr"],
    onCodeScanned: handleCodeScanned,
  });

  // Detecta si se tienen permisos para usar la camara y si no, los pide.
  useEffect(() => {
    if (!hasPermission) {
      requestPermission();
    }
  }, [hasPermission]);

  if (!hasPermission) {
    return <ActivityIndicator />;
  }

  if (!device & !isFocussed) return <Text>Camara no encontrada</Text>;

  if ((device != null) & isFocussed) {
    return (
      <View style={styles.container}>
        <GestureDetector gesture={gesture}>
          <ReanimatedCamera
            style={styles.camera}
            device={device}
            isActive={isActive}
            enableZoomGesture={true}
            codeScanner={codeScanner}
            animatedProps={animatedProps}
          />
        </GestureDetector>
        <View style={styles.sliderContainer}>
          <Text>Ajustar Brillo</Text>
          <Slider
            value={exposureSlider.value}
            onValueChange={(value) => {
              exposureSlider.value = value;
            }}
            minimumValue={-1}
            maximumValue={1}
            step={0.1}
          />
        </View>
      </View>
    );
  }
}
