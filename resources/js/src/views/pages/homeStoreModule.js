import axios from "@axios";

export default {
  namespaced: true,
  getters: {},
  actions: {
    fetchSlides(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/slide", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    fetchSlide(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/slide/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    addSlide(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      return new Promise((resolve, reject) => {
        axios
          .post("/slide", form_data, {
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

    editSlide(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/slide/${dataSend.id}`, form_data, {
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

    deleteSlide(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/slide/${id}`)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editLevelSlide(ctx, dataSend) {
      return new Promise((resolve, reject) => {
        axios
          .put(`/slide/level/${dataSend.id}`, dataSend)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    fetchHosts(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/host", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },

    fetchCountries(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/country", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },

    fetchActivities(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/activity", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    //
    addActivity(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      return new Promise((resolve, reject) => {
        axios
          .post("/activity", form_data, {
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

    editActivity(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/activity/${dataSend.id}`, form_data, {
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

    deleteActivity(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/activity/${id}`)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },
  },
};
