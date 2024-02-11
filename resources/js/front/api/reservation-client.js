import { client } from "./api-client";
import { format } from "date-fns";

const SITE_ID = process.env.MIX_SITE_ID;

/**
 * Récupère la liste des assurances
 * @param array params
 */
const assurances = async ({ coordonnees, accompagnants, projet }) => {
  const params = getParams({ coordonnees, accompagnants, projet });
  return await client("search-quote", "post", params);
};

/**
 * Récupérer le SHASIGN pour le paiement
 * @param array params
 */
const reservation = async ({
  coordonnees,
  accompagnants,
  projet,
  assurance,
}) => {
  const params = getParams({ coordonnees, accompagnants, projet, assurance });
  return await client("commandes", "post", params);
};

/**
 * Créer l'objet params pour les requêtes
 * @param {*} param0
 * @returns
 */
const getParams = ({ coordonnees, accompagnants, projet, assurance }) => {
  let items = [];

  for (let i = 0; i < accompagnants.length; i++) {
    items.push({
      nom: accompagnants[i].nom,
      prenom: accompagnants[i].prenom,
      date_naissance: format(accompagnants[i].dateNaissance, "yyyy-MM-dd"),
    });
  }

  let params = {
    voyageur: {
      civilite: coordonnees.civilite,
      nom: coordonnees.nom,
      prenom: coordonnees.prenom,
      date_naissance: format(coordonnees.dateNaissance, "yyyy-MM-dd"),
      telephone: coordonnees.telephone,
      email: coordonnees.email,
      email_confirmation: coordonnees.emailConfirm,
      cp: coordonnees.codePostal,
      ville: coordonnees.ville,
      adresse: coordonnees.adresse,
      adresse2: coordonnees.adresse2,
    },
    accompagnants: items,
    prix_voyage: parseInt(projet.montant, 10) * 100,
    date_depart: format(projet.dates.from, "yyyy-MM-dd"),
    date_retour: format(projet.dates.to, "yyyy-MM-dd"),
    code_pays_destination: projet.destination.alpha2,
  };

  if (assurance !== undefined) {
    params.site_id = SITE_ID;

    params.assurance = {
      id: assurance.ref,
      priceline: assurance.priceline,
      prix: assurance.prix,
      nom: assurance.nom,
    };
  }

  return params;
};

export { assurances, reservation };
