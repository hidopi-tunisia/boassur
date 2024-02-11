import React from "react";

import {
  Projet,
  Assurances,
  Coordonnees,
  Accompagnants,
  Recapitulatif,
} from "./etapes";

const initialState = {
  etape: 0,
  projet: null,
  assurances: [],
  assurance: null,
  coordonnees: null,
  accompagnants: [],
};

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "PROJET":
      local.projet = action.payload;
      local.assurances = [];
      local.assurance = null;
      local.coordonnees = null;

      // Réinitialiser les accompagnants si leur nombre change
      const num = parseInt(local.projet.voyageurs.accompagnants, 10);
      if (local.accompagnants.length !== num) {
        local.accompagnants = [];
        for (let i = 0; i < num; i++) {
          local.accompagnants.push({
            nom: "",
            prenom: "",
            dateNaissance: null,
          });
        }
      }

      local.etape = 1;
      break;
    case "COORDONNEES":
      local.coordonnees = action.payload;
      // On passe directement aux assurances si pas d'accompagnants
      local.etape =
        parseInt(local.projet.voyageurs.accompagnants, 10) === 0 ? 3 : 2;
      break;
    case "ACCOMPAGNANTS":
      local.accompagnants = action.payload;
      local.etape = 3;
      break;
    case "ASSURANCE":
      local.assurances = action.payload.assurances;
      local.assurance = action.payload.assurance;
      local.etape = 4;
      break;
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

const Reservation = () => {
  const [state, dispatch] = React.useReducer(reducer, initialState);

  const updateProjet = React.useCallback((data) => {
    dispatch({ type: "PROJET", payload: data });
  }, []);

  const updateAssurance = React.useCallback((data) => {
    dispatch({ type: "ASSURANCE", payload: data });
  }, []);

  const updateCoordonnees = React.useCallback((data) => {
    dispatch({ type: "COORDONNEES", payload: data });
  }, []);

  const updateAccompagnants = React.useCallback((data) => {
    dispatch({ type: "ACCOMPAGNANTS", payload: data });
  }, []);

  let content;
  switch (state.etape) {
    case 0:
      content = <Projet data={state.projet} callback={updateProjet} />;
      break;
    case 1:
      content = (
        <Coordonnees
          data={state.coordonnees}
          projet={state.projet}
          callback={updateCoordonnees}
        />
      );
      break;
    case 2:
      content = (
        <Accompagnants
          data={state.accompagnants}
          callback={updateAccompagnants}
        />
      );
      break;
    case 3:
      content = (
        <Assurances
          projet={state.projet}
          coordonnees={state.coordonnees}
          accompagnants={state.accompagnants}
          data={state.assurances}
          callback={updateAssurance}
        />
      );
      break;
    case 4:
      content = (
        <Recapitulatif
          projet={state.projet}
          coordonnees={state.coordonnees}
          assurance={state.assurance}
          accompagnants={state.accompagnants}
        />
      );
      break;
    default:
      content = null;
  }

  return (
    <div className="container mx-auto my-4 p-6 text-xl text-gray-600 space-y-12">
      {content}
    </div>
  );
};

export default Reservation;
