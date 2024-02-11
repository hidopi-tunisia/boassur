import React from "react";

import { useOnClickOutside } from "../../../utils/hooks";

const Select = ({ callback }) => {
  const [state, setState] = React.useState({
    open: false,
    type: null,
    value: "",
  });
  const ref = React.useRef();

  const closeDropdown = () => {
    setState({ ...state, open: false });
  };

  useOnClickOutside(ref, closeDropdown);

  const handleFocus = () => {
    setState({ ...state, open: true });
  };

  const handleClick = (event) => {
    setState({
      open: false,
      type: event.target.value,
      value: event.target.value === "1" ? "seul" : "à plusieurs",
    });

    callback(event.target.value);
  };

  return (
    <div className="relative flex-none">
      <input
        className={
          state.type === "2"
            ? "input-projet w-32 text-center"
            : "input-projet w-16 text-center"
        }
        onFocus={handleFocus}
        value={state.value}
        readOnly
      />
      {state.open ? (
        <div
          ref={ref}
          className="border absolute object-left-top shadow-lg bg-white z-50"
        >
          <ul className="w-40 list-none divide-y">
            <li className="flex justify-start">
              <button
                type="button"
                value="1"
                className="flex-1 py-2 px-4 border border-transparent bg-white hover:bg-red-100 focus:outline-none focus:ring-0 focus:ring-transparent"
                onClick={handleClick}
              >
                Seul
              </button>
            </li>
            <li className="flex justify-start">
              <button
                type="button"
                value="2"
                className="flex-1 py-2 px-4 border border-transparent bg-white hover:bg-red-100 focus:outline-none focus:ring-0 focus:ring-transparent"
                onClick={handleClick}
              >
                À plusieurs
              </button>
            </li>
          </ul>
        </div>
      ) : null}
    </div>
  );
};

const Accompagnants = ({ callback }) => {
  const [state, setState] = React.useState("");

  const onChange = (event) => {
    setState(event.target.value);
    callback(event.target.value);
  };

  return (
    <>
      <label>, accompagné de</label>
      <input
        className={`input-projet w-16 flex-none text-center ${
          parseInt(state, 10) > 8 ? "text-red-600" : ""
        }`}
        value={state}
        onChange={onChange}
      />
      <label>personnes</label>
    </>
  );
};

const Voyageurs = ({ data, callback }) => {
  const [state, setState] = React.useState({ ...data });

  const updateVoyageur = (type) => {
    const value = {
      type,
      accompagnants: type === "2" ? state.accompagnants : 0,
    };
    setState(value);
    callback({ key: "voyageurs", payload: value });
  };

  const updateAccompagnant = (num) => {
    const value = {
      type: "2",
      accompagnants: num,
    };
    setState(value);
    callback({ key: "voyageurs", payload: value });
  };

  return (
    <div className="relative flex justify-start items-center">
      <label>Je m'en vais</label>
      <Select callback={updateVoyageur} />
      {state.type === "2" ? (
        <Accompagnants callback={updateAccompagnant} />
      ) : (
        "."
      )}
    </div>
  );
};

export default Voyageurs;
