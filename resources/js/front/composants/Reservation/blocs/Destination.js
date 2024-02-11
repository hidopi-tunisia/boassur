import React from "react";
import axios from "axios";
import debounce from "lodash.debounce";

import { useOnClickOutside } from "../../../utils/hooks";

const API_URL = process.env.MIX_APP_API_URL;
const CancelToken = axios.CancelToken;
let source;

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "SOURCE":
      const num = action.payload.data.length;
      if (num === 0 || local.terme === "") {
        // Aucun résultats ou terme vide on réinitialise
        local.destination = null;
        local.items = [];
      }

      if (num >= 1 && local.terme !== "") {
        local.destination = null;
        local.items = action.payload.data;
      }

      local.open = true;
      break;
    case "SELECTED":
      local.destination = local.items[action.payload];
      local.items = [];
      local.terme = `${local.destination.article} ${local.destination.nom}`;
      local.open = false;
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

const Pays = ({ items, callback }) => {
  if (items.length === 0) {
    return null;
  }

  let rows = [];
  items.map((item, index) =>
    rows.push(
      <li key={`pays-${index}`}>
        <button
          value={index}
          onClick={callback}
          className="w-full py-2 px-4 rounded-0 bg-white hover:bg-red-100 focus:outline-none transition duration-150 ease-in-out"
        >
          {item.nom}
        </button>
      </li>
    )
  );

  return <ul className="list-none divide-y">{rows}</ul>;
};

const Destination = ({ data, callback }) => {
  const [state, dispatch] = React.useReducer(reducer, {
    open: false,
    terme: data ? data.nom : "",
    selected: data ? 0 : null,
    destination: data,
    items: [],
  });

  const ref = React.useRef();

  const closeDropdown = () => {
    dispatch({ type: "UPDATE", key: "open", payload: false });
  };

  useOnClickOutside(ref, closeDropdown);

  const fetchDestinations = React.useCallback(async (terme) => {
    source = CancelToken.source();
    try {
      const res = await axios.get(`${API_URL}destinations?terme=${terme}`, {
        cancelToken: source.token,
      });

      dispatch({ type: "SOURCE", payload: res.data });
    } catch (error) {
      dispatch({ type: "UPDATE", key: "items", payload: [] });
      dispatch({ type: "UPDATE", key: "open", payload: false });
    }
  }, []);

  /* eslint-disable react-hooks/exhaustive-deps */
  const debounceFetch = React.useCallback(
    debounce((data) => fetchDestinations(data), 800),
    []
  );

  const onItemSelected = (event) => {
    dispatch({ type: "SELECTED", payload: event.target.value });
    callback({ key: "destination", payload: state.items[event.target.value] });
  };

  const onInputChange = (event) => {
    const terme = event.target.value;

    if (source !== undefined) {
      source.cancel();
    }

    dispatch({ type: "UPDATE", key: "terme", payload: terme });

    if (terme === "") {
      // Vider les options
      dispatch({ type: "UPDATE", key: "items", payload: [] });
      callback({ key: "destination", payload: null });
      return;
    }

    if (state.destination !== null && state.destination.nom === terme) {
      return;
    }

    debounceFetch(terme);
  };

  const onInputFocus = () => {
    if (state.items.length > 0 && state.open === false) {
      dispatch({ type: "UPDATE", key: "open", payload: true });
    }
  };

  return (
    <div className="relative flex justify-start items-center">
      <label>Je pars</label>
      <div className="relative">
        <input
          className="input-projet w-80"
          value={state.terme}
          onChange={onInputChange}
          onFocus={onInputFocus}
        />
        {state.open ? (
          <div
            ref={ref}
            className="border absolute object-left-top shadow-lg bg-white z-50"
          >
            <Pays items={state.items} callback={onItemSelected} />
          </div>
        ) : null}
      </div>
    </div>
  );
};

export default Destination;
