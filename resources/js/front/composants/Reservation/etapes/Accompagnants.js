import React from "react";
import DayPickerInput from "react-day-picker/DayPickerInput";
import "react-day-picker/lib/style.css";

import { DaypickerMonthForm } from "../blocs";
import { DATEFORMAT, DATELOCALE } from "../../../constants";
import { formatDate } from "../../../utils/formatters";

const minDate = new Date();
const minYear = minDate.getFullYear();
const fromMonth = new Date(minYear - 70, 0);
const toMonth = new Date(minYear, 11);

const reducer = (prevState, action) => {
  let local = { ...prevState };

  switch (action.type) {
    case "UPDATE":
      if (Object.keys(local).includes(action.key)) {
        local[action.key] = action.payload;
      }
      break;
    case "ACCOMPAGNANT":
      local.accompagnants[action.id][action.key] = action.value;
      break;
    default:
      break;
  }

  return local;
};

const Item = ({ id, data, dirty, errors, callback }) => {
  const [state, setState] = React.useState({ month: minDate });

  const onChange = (event) => {
    callback(id, event.target.name, event.target.value);
  };

  const handleDateChange = (date) => {
    callback(id, "dateNaissance", date);
  };

  return (
    <div className="border border-gray-200 divide-y-1 divide-gray-200">
      <div className="text-lg p-4 bg-gray-100">{`Accompagnant ${id + 1}`}</div>
      <div className="p-3">
        <label htmlFor={`nom${id}`} className="form-label">
          Nom
        </label>
        <input
          type="text"
          id={`nom${id}`}
          name="nom"
          className="input-text"
          value={data.nom}
          onChange={onChange}
        />
        <div className="resa-error">
          {dirty && errors.includes("nom") ? "Le nom est obligatoire" : ""}
        </div>
      </div>
      <div className="p-3">
        <label htmlFor={`prenom${id}`} className="form-label">
          Prénom
        </label>
        <input
          type="text"
          id={`prenom${id}`}
          name="prenom"
          className="input-text"
          value={data.prenom}
          onChange={onChange}
        />
        <div className="resa-error">
          {dirty && errors.includes("prenom") ? "Le nom est obligatoire" : ""}
        </div>
      </div>
      <div className="p-3">
        <label htmlFor={`date${id}`} className="form-label">
          Date de naissance
        </label>
        <div className="text-sm">
          <DayPickerInput
            value={data.dateNaissance}
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
                  onChange={(month) => {
                    setState({ month });
                  }}
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
                id={`date${id}`}
                name="dateNaissance"
                readOnly
              />
            ))}
          />
        </div>
        <div className="resa-error">
          {dirty && errors.includes("date") ? "Le nom est obligatoire" : ""}
        </div>
      </div>
    </div>
  );
};

const Accompagnants = ({ data, callback }) => {
  const [state, dispatch] = React.useReducer(reducer, {
    prune: true,
    errors: [],
    accompagnants: data,
  });

  const itemCallback = (id, key, value) => {
    dispatch({ type: "ACCOMPAGNANT", id, key, value });
    validate();
  };

  const validate = () => {
    let errors = [];

    for (let i = 0; i < state.accompagnants.length; i++) {
      let error = [];
      if (state.accompagnants[i].nom === "") {
        error.push("nom");
      }
      if (state.accompagnants[i].prenom === "") {
        error.push("prenom");
      }
      if (state.accompagnants[i].dateNaissance === null) {
        error.push("date");
      }

      errors.push(error);
    }

    dispatch({
      type: "UPDATE",
      key: "errors",
      payload: errors,
    });

    if (errors.flat().length === 0) {
      return true;
    }

    return false;
  };

  const submit = (event) => {
    event.preventDefault();

    if (validate()) {
      callback(state.accompagnants);
    }

    if (state.prune === true) {
      dispatch({ type: "UPDATE", key: "prune", payload: false });
    }
  };

  return (
    <div className="resa-container">
      <h1 className="resa-title">Information accompagnants</h1>
      <div className="grid grid-cols-4 gap-8">
        {data.map((val, key) => {
          return (
            <Item
              data={val}
              dirty={!state.prune}
              errors={state.errors[key]}
              id={key}
              key={`acc-${key}`}
              callback={itemCallback}
            />
          );
        })}
      </div>
      <div className="py-6 text-center">
        <button type="submit" className="btn-primary" onClick={submit}>
          Je choisis mon assurance
        </button>
      </div>
    </div>
  );
};

export default Accompagnants;
