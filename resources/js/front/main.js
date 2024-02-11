import React from "react";
import ReactDOM from "react-dom";

import Reservation from "./composants/Reservation/Reservation";

if (document.getElementById("pre-resa")) {
  ReactDOM.render(<Reservation />, document.getElementById("pre-resa"));
}
