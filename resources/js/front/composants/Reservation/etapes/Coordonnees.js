import React from "react";
import DayPickerInput from "react-day-picker/DayPickerInput";
import { sub } from "date-fns";
import "react-day-picker/lib/style.css";

import { DaypickerMonthForm } from "../blocs";
import { DATEFORMAT, DATELOCALE } from "../../../constants";
import { formatDate } from "../../../utils/formatters";

const minDate = sub(new Date(), { years: 21 }); // 21 ans révolus
const minYear = minDate.getFullYear();
const fromMonth = new Date(minYear - 70, 0);
const toMonth = new Date(minYear, 11);

const initialState = {
  prune: true,
  errors: [],
  month: minDate,
  civilite: "",
  nom: "",
  prenom: "",
  dateNaissance: null,
  telephone: "",
  email: "",
  emailConfirm: "",
  codePostal: "",
  ville: "",
  adresse: "",
  adresse2: "",
};

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "UPDATE":
      if (Object.keys(local).includes(action.key)) {
        local[action.key] = action.payload;
      }
      break;
    default:
      break;
  }

  return local;
};

const Coordonnees = ({ data, projet, callback }) => {
  const [state, dispatch] = React.useReducer(reducer, {
    ...initialState,
    ...data,
  });

  const onChange = (event) => {
    dispatch({
      type: "UPDATE",
      key: event.target.id,
      payload: event.target.value,
    });

    if (state.prune === false) {
      validate();
    }
  };

  const handleDateChange = (date) => {
    dispatch({
      type: "UPDATE",
      key: "dateNaissance",
      payload: date,
    });

    if (state.prune === false) {
      validate();
    }
  };

  const handleYearMonthChange = (month) => {
    dispatch({
      type: "UPDATE",
      key: "month",
      payload: month,
    });
  };

  const validate = () => {
    const keys = [
      "civilite",
      "nom",
      "prenom",
      "dateNaissance",
      "telephone",
      "email",
      "codePostal",
      "ville",
      "adresse",
    ];
    let errors = [];

    for (let key of keys) {
      if (state[key] === "" || state[key] === null) {
        errors.push(key);
      }
    }

    if (
      state.email !== "" &&
      /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(
        state.email
      ) === false
    ) {
      errors.push("emailValide");
    }

    if (state.email !== "" && state.emailConfirm !== state.email) {
      errors.push("emailConfirm");
    }

    dispatch({
      type: "UPDATE",
      key: "errors",
      payload: errors,
    });

    if (errors.length === 0) {
      return true;
    }

    return false;
  };

  const submit = (event) => {
    event.preventDefault();

    if (validate()) {
      const payload = (({
        civilite,
        nom,
        prenom,
        dateNaissance,
        telephone,
        email,
        emailConfirm,
        codePostal,
        ville,
        adresse,
        adresse2,
      }) => ({
        civilite,
        nom,
        prenom,
        dateNaissance,
        telephone,
        email,
        emailConfirm,
        codePostal,
        ville,
        adresse,
        adresse2,
      }))(state);

      callback(payload);
    }

    if (state.prune === true) {
      dispatch({ type: "UPDATE", key: "prune", payload: false });
    }
  };

  return (
    <div className="resa-container">
      <h1 className="resa-title">Mes informations personnelles</h1>
      <div className="grid grid-cols-12 gap-4">
        <div className="col-span-4 sm:col-span-2 lg:col-span-2">
          <label htmlFor="civilite" className="form-label">
            Civilité
          </label>
          <select onChange={onChange} id="civilite" className="input-text">
            <option></option>
            <option value={1}>Monsieur</option>
            <option value={2}>Madame</option>
          </select>
          <div className="resa-error">
            {state.errors.includes("civilite")
              ? "La civilité est obligatoire"
              : ""}
          </div>
        </div>
        <div className="col-span-12 sm:col-span-5 lg:col-span-4">
          <label htmlFor="nom" className="form-label">
            Nom
          </label>
          <input
            value={state.nom}
            onChange={onChange}
            type="text"
            id="nom"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("nom") ? "Le nom est obligatoire" : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-5 lg:col-span-4">
          <label htmlFor="prenom" className="form-label">
            Prénom
          </label>
          <input
            value={state.prenom}
            onChange={onChange}
            type="text"
            id="prenom"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("prenom") ? "Le prénom est obligatoire" : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-4 lg:col-span-2">
          <label htmlFor="dateNaissance" className="form-label">
            Date de naissance
          </label>
          <div className="text-sm">
            <DayPickerInput
              value={state.dateNaissance}
              placeholder=""
              format={DATEFORMAT}
              formatDate={formatDate}
              dayPickerProps={{
                ...DATELOCALE,
                month: state.month,
                fromMonth,
                toMonth,
                disabledDays: { after: minDate },
                numberOfMonths: 1,
                captionElement: ({ date, localeUtils }) => (
                  <DaypickerMonthForm
                    date={date}
                    localeUtils={localeUtils}
                    onChange={handleYearMonthChange}
                    fromMonth={fromMonth}
                    toMonth={toMonth}
                  />
                ),
              }}
              onDayChange={handleDateChange}
              component={React.forwardRef((props, ref) => (
                <input
                  type="text"
                  ref={ref}
                  {...props}
                  className="input-text"
                  readOnly
                />
              ))}
            />
          </div>
          <div className="resa-error">
            {state.errors.includes("dateNaissance")
              ? "La date est obligatoire"
              : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-4 lg:col-span-4">
          <label htmlFor="email" className="form-label">
            Email
          </label>
          <input
            value={state.email}
            onChange={onChange}
            type="text"
            id="email"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("email") ? "L'email est obligatoire" : ""}
            {state.errors.includes("emailValide")
              ? "L'email doit être valide"
              : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-4 lg:col-span-4">
          <label htmlFor="emailConfirm" className="form-label">
            Confirmer votre email
          </label>
          <input
            value={state.emailConfirm}
            onChange={onChange}
            type="text"
            id="emailConfirm"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("emailConfirm")
              ? "L'email doit être confirmé"
              : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-4 lg:col-span-4">
          <label htmlFor="telephone" className="form-label">
            Téléphone
          </label>
          <input
            value={state.telephone}
            onChange={onChange}
            type="text"
            id="telephone"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("telephone")
              ? "Le téléphone est obligatoire"
              : ""}
          </div>
        </div>

        <h2 className="col-span-12">Adresse</h2>

        <div className="col-span-12 sm:col-span-6">
          <label htmlFor="adresse" className="form-label">
            Numéro et voie
          </label>
          <input
            value={state.adresse}
            onChange={onChange}
            type="text"
            id="adresse"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("adresse")
              ? "L'adresse est obligatoire"
              : ""}
          </div>
        </div>

        <div className="col-span-12 sm:col-span-6">
          <label htmlFor="adresse2" className="form-label">
            Complément d'adresse
          </label>
          <input
            value={state.adresse2}
            onChange={onChange}
            type="text"
            id="adresse2"
            className="input-text"
          />
        </div>

        <div className="col-span-4 sm:col-span-3  lg:col-span-2">
          <label htmlFor="codePostal" className="form-label">
            Code postal
          </label>
          <input
            value={state.codePostal}
            onChange={onChange}
            type="text"
            id="codePostal"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("codePostal")
              ? "Le code postal est obligatoire"
              : ""}
          </div>
        </div>

        <div className="col-span-8 sm:col-span-5">
          <label htmlFor="ville" className="form-label">
            Ville
          </label>
          <input
            value={state.ville}
            onChange={onChange}
            type="text"
            id="ville"
            className="input-text"
          />
          <div className="resa-error">
            {state.errors.includes("ville") ? "La ville est obligatoire" : ""}
          </div>
        </div>
      </div>
      <div className="py-6 text-center">
        <button type="submit" className="btn-primary" onClick={submit}>
          {projet.voyageurs.accompagnants === 0
            ? "Je choisis mon assurance"
            : "Je renseigne mes accompagnants"}
        </button>
      </div>
      <div className="pb-4 text-sm">
        En saisissant ces informations, vous acceptez nos dispositions
        concernant le traitement de vos données personnelles : cliquez ici pour
        plus de détails
      </div>
    </div>
  );
};

export default Coordonnees;
