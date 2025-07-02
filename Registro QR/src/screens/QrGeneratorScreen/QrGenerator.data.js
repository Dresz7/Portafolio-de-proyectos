import * as Yup from "yup";

export function initailvalues() {
  return {
    qrValue: "",
  };
}
export function validationSchema() {
  return Yup.object({
    qrValue: Yup.string().required("Este campo es obligatorio"),
  });
}
