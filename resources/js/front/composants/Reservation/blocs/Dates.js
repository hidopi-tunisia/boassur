import React from "react";
import DayPickerInput from "react-day-picker/DayPickerInput";
import { add } from "date-fns";
import "react-day-picker/lib/style.css";

import { DATEFORMAT, DATELOCALE } from "../../../constants";
import { formatDate } from "../../../utils/formatters";

const minDate = add(new Date(), { days: 1 });

const DEFAULT_OPTIONS = {
  placeholder: "",
  format: DATEFORMAT,
  formatDate,
};

const Dates = ({ data, callback }) => {
  const [state, setState] = React.useState({ ...data });

  const handleFromChange = (from) => {
    setState({ to: state.to, from });
    callback({ key: "dates", payload: { to: state.to, from } });
  };

  const handleToChange = (to) => {
    setState({ from: state.from, to });
    callback({ key: "dates", payload: { from: state.from, to } });
  };

  return (
    <div className="flex items-center space-x-4">
      <label>Je pars du</label>
      <div className="text-sm">
        <DayPickerInput
          {...DEFAULT_OPTIONS}
          value={state.from}
          dayPickerProps={{
            ...DATELOCALE,
            selectedDays: [state.from, { ...state }],
            disabledDays: { before: minDate, after: state.to },
            toMonth: state.to,
            modifiers: { start: state.from, end: state.to },
            numberOfMonths: 1,
          }}
          onDayChange={handleFromChange}
          component={React.forwardRef((props, ref) => (
            <input
              ref={ref}
              {...props}
              className="input-projet w-32 text-center"
              readOnly
            />
          ))}
        />
      </div>
      <label>au</label>
      <div className="text-sm">
        <DayPickerInput
          {...DEFAULT_OPTIONS}
          value={state.to}
          dayPickerProps={{
            ...DATELOCALE,
            selectedDays: [state.from, { ...state }],
            disabledDays: { before: state.from },
            modifiers: { start: state.from, end: state.to },
            month: state.from,
            fromMonth: state.from,
            numberOfMonths: 1,
          }}
          onDayChange={handleToChange}
          component={React.forwardRef((props, ref) => (
            <input
              ref={ref}
              {...props}
              className="input-projet w-32 text-center"
              readOnly
            />
          ))}
        />
      </div>
    </div>
  );
};

export default Dates;
