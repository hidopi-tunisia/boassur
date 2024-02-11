import React from "react";
import { useDeepCompareEffect } from "react-use";

import { assurances } from "../../../api/reservation-client";

const initialState = {
  loading: true,
  assurances: [],
};

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "UPDATE":
      if (Object.keys(local).includes(action.key)) {
        local[action.key] = action.payload;
      }
      break;
    case "FETCHED":
      local.assurances = action.payload;
      local.loading = false;
      break;
    default:
      break;
  }

  return local;
};

const Assurances = ({ coordonnees, accompagnants, projet, data, callback }) => {
  const [state, dispatch] = React.useReducer(reducer, {
    ...initialState,
    assurances: data,
  });

  useDeepCompareEffect(() => {
    const fetchData = async () => {
      const res = await assurances({ coordonnees, accompagnants, projet });
      if (res.success === true && res.body.length > 0) {
        dispatch({ type: "FETCHED", payload: res.body });
      } else {
      }
    };

    fetchData();
  }, [coordonnees, accompagnants, projet]);

  const onClick = (event) => {
    callback({
      assurance: state.assurances[parseInt(event.target.value, 10)],
      assurances: state.assurances,
    });
  };

  if (state.loading) {
    return (
      <div className="py-24 text-center text-lg">
        Nous recherchons les assurances qui correspondent à vos critères,
        veuillez patienter...
      </div>
    );
  }

  // Si pas de résultats retour aux critères
  if (state.assurances.length === 0) {
    return <div className="py-12">Aucun résultat</div>;
  }

  return (
    <div className="resa-container">
      <h1 className="resa-title">Je choisis mon assurance</h1>
      <div className="grid grid-cols-4 gap-6">
        {state.assurances.map((item, index) => (
          <div
            className="flex flex-col justify-between p-3 border border-gray-200 bg-white shadow-sm hover:shadow-lg hover:bg-red-50"
            key={`pays-${index}`}
          >
            <div className="mb-6">
              <span className="text-sm">{item.nom}</span>
              <div className="text-lg text-red-700">{item.prix_eur}</div>
            </div>
            <button
              value={index}
              onClick={onClick}
              className="btn-primary w-full"
            >
              Réserver
            </button>
          </div>
        ))}
      </div>
    </div>
  );
};

export default Assurances;
