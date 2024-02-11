import React from "react";

import { Destination, Dates, Voyageurs, Montant } from "../blocs";

let initialState = {
  prune: true,
  errors: [],
  destination: null,
  voyageurs: { type: null, accompagnants: 0 },
  dates: { from: undefined, to: undefined },
  montant: "",
};

const validate = (state) => {
  let errors = [];
  if (state.destination === null) {
    errors.push("destination");
  }
  if (state.voyageurs.type === null) {
    errors.push("voyageurs");
  }
  if (
    parseInt(state.voyageurs.type, 10) === 2 &&
    (parseInt(state.voyageurs.accompagnants, 10) === 0 ||
      parseInt(state.voyageurs.accompagnants, 10) > 8)
  ) {
    errors.push("accompagnants");
  }
  if (state.dates.from === undefined) {
    errors.push("dateDepart");
  }
  if (state.dates.to === undefined) {
    errors.push("dateRetour");
  }
  if (state.montant === "") {
    errors.push("montant");
  }

  return errors;
};

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "UPDATE":
      if (Object.keys(local).includes(action.key)) {
        local[action.key] = action.payload;
      }

      if (local.prune === false) {
        local.errors = validate(local);
      }

      break;
    default:
      break;
  }

  return local;
};

const Projet = ({ data, callback }) => {
  const [state, dispatch] = React.useReducer(reducer, {
    ...initialState,
    ...data,
  });

  const update = (data) => {
    dispatch({ type: "UPDATE", key: data.key, payload: data.payload });
  };

  const submit = (event) => {
    event.preventDefault();

    const errors = validate(state);
    dispatch({ type: "UPDATE", key: "errors", payload: errors });

    if (errors.length === 0) {
      callback({
        destination: state.destination,
        voyageurs: state.voyageurs,
        dates: state.dates,
        montant: state.montant,
      });
    }

    if (state.prune === true) {
      dispatch({ type: "UPDATE", key: "prune", payload: false });
    }
  };

  return (
    <div className="resa-container">
      <h1 className="resa-title">Mon projet de voyage</h1>
      <div>
        <Destination data={state.destination} callback={update} />
        {state.errors.includes("destination") ? (
          <div className="resa-error mt-0">La destination est obligatoire</div>
        ) : null}
      </div>
      <div>
        <Voyageurs data={state.voyageurs} callback={update} />
        {state.errors.includes("voyageurs") ? (
          <div className="resa-error">
            Le nombre de voyageurs est obligatoire
          </div>
        ) : null}
        {state.errors.includes("accompagnants") ? (
          <div className="resa-error">
            Le nombre d'accompagnants est obligatoire et ne doit pas dépasser 8
          </div>
        ) : null}
      </div>
      <div>
        <Dates data={state.dates} callback={update} />
        {state.errors.includes("dateDepart") &&
        state.errors.includes("dateRetour") ? (
          <div className="resa-error">
            Les dates de départ et de retour sont obligatoires
          </div>
        ) : null}
        {state.errors.includes("dateDepart") &&
        !state.errors.includes("dateRetour") ? (
          <div className="resa-error">La date de départ est obligatoire</div>
        ) : null}
        {state.errors.includes("dateRetour") &&
        !state.errors.includes("dateDepart") ? (
          <div className="resa-error">La date de retour est obligatoire</div>
        ) : null}
      </div>
      <div>
        <Montant data={state.montant} callback={update} />
        {state.errors.includes("montant") ? (
          <div className="resa-error">Le montant est obligatoire</div>
        ) : null}
      </div>
      <div className="py-6 text-center">
        <button type="button" className="btn-primary" onClick={submit}>
          Je renseigne mes informations personnelles
        </button>
      </div>
    </div>
  );
};

export default Projet;
