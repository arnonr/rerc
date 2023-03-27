import axios from "@axios";

export default {
  namespaced: true,
  getters: {},
  actions: {
    fetchPortfolios(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/portfolio", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    fetchPortfolio(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/portfolio/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    addPortfolio(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      return new Promise((resolve, reject) => {
        axios
          .post("/portfolio", form_data, {
            headers: {
              "content-type": "multipart/form-data",
            },
          })
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editPortfolio(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/portfolio/${dataSend.id}`, form_data, {
            headers: {
              "content-type": "multipart/form-data",
            },
          })
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    deletePortfolio(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/portfolio/${id}`)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editLevelPortfolio(ctx, dataSend) {
      return new Promise((resolve, reject) => {
        axios
          .put(`/portfolio/level/${dataSend.id}`, dataSend)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },
  },
};
