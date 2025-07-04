import React from "react";
import { Overlay } from "@rneui/themed";
import { styles } from "./Modal.styles";

export function Modal(props) {
  const { show, close, children } = props;
  return (
    <Overlay
      isVisible={show}
      overlayStyle={styles.overlay}
      onBackdropPress={close}
    >
      {children}
    </Overlay>
  );
}
