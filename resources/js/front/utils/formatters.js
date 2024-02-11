import { format } from "date-fns";

/**
 * Formate la date pour DayPicker
 * @param {*} date La date
 * @param {*} form Le format
 * @returns string
 */
const formatDate = (date, form) => {
  return format(date, form);
};

export {formatDate}