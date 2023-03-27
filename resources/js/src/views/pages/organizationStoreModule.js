import axios from "@axios";

export default {
  namespaced: true,
  getters: {},
  actions: {
    fetchOrganization(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/organization/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },

    editOrganization(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/organization/${dataSend.id}`, form_data, {
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
  },
};
