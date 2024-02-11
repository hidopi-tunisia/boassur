import React from "react";

const Montant = ({ data, callback }) => {
  const [state, setState] = React.useState(data);

  const onChange = (event) => {
    setState(event.target.value);
    callback({ key: "montant", payload: event.target.value });
  };

  return (
    <div className="relative flex justify-start items-center">
      <label>Je souhaite assurer ce voyage d'un montant de</label>
      <input
        className="input-projet flex-none w-24 text-center"
        value={state}
        onChange={onChange}
      />
      €
    </div>
  );
};

export default Montant;
