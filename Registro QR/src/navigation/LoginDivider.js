import React, { useState, useEffect } from "react";
import { getAuth, onAuthStateChanged } from "firebase/auth";
import { AppNavigation } from "./AppNavigation";
import { LoginStack } from "./LoginStack";
import { LoadingModal } from "../components";

// Funcion redirigue al login o al AppNavigation dependiendo hasLogged
export function LoginDivider() {
  const [hasLogged, setHasLogged] = useState(null);

  // Aqui se detecta si se inicion sesion
  useEffect(() => {
    const auth = getAuth();
    onAuthStateChanged(auth, (user) => {
      setHasLogged(user ? true : false);
    });
  }, []);

  if (hasLogged === null) {
    return <LoadingModal show text="Cargando" />;
  }

  return hasLogged ? <AppNavigation /> : <LoginStack />;
}
