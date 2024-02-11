// require('./bootstrap');
import { subYears } from "date-fns";
import "livewire-sortable";

window.pikadayMaxDate = subYears(new Date(), 18);

window.pikadayTranslations = {
  previousMonth: "Préc.",
  nextMonth: "Suiv.",
  months: [
    "Janvier",
    "Février",
    "Mars",
    "Avril",
    "Mai",
    "Juin",
    "Juiller",
    "Août",
    "Septembre",
    "Octobre",
    "Novembre",
    "Décembre",
  ],
  weekdays: [
    "Dimanche",
    "Lundi",
    "Mardi",
    "Mercredi",
    "Jeudi",
    "Vendredi",
    "Samedi",
  ],
  weekdaysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
};

/**
 * Formate une date js en dd/mm/aaaa
 * @param {Date} date La date à formater
 * @returns String
 */
window.formatPikadayDate = (date) => {
  let day = date.getDate();
  let month = date.getMonth() + 1;
  const year = date.getFullYear();

  day = day < 10 ? `0${day}` : day;
  month = month < 10 ? `0${month}` : month;
  return `${day}/${month}/${year}`;
};
