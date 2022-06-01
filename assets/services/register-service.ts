import axios from "axios";

/**
 *
 * @param form
 */
export function verifyRegistration(form: object) {
    return axios.post(`/register/verify/registration`, form);
}