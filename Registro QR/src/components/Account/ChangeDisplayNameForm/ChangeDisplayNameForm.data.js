import * as Yup from "yup";

export function initailvalues() {
  return {
    displayName: "",
  };
}
export function validationSchema() {
  return Yup.object({
    displayName: Yup.string().required("El nombre y apellidos son requeridos"),
  });
}
