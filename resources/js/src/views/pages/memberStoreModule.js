import axios from "@axios";

export default {
  namespaced: true,
  getters: {},
  actions: {
    fetchMembers(ctx, queryParams) {
      return new Promise((resolve, reject) => {
        axios
          .get("/member", { params: queryParams })
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    fetchMember(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .get(`/member/${id}`)
          .then((response) => resolve(response))
          .catch((error) => reject(error));
      });
    },
    addMember(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      return new Promise((resolve, reject) => {
        axios
          .post("/member", form_data, {
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

    editMember(ctx, dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }

      form_data.append("_method", "PUT");

      return new Promise((resolve, reject) => {
        axios
          .post(`/member/${dataSend.id}`, form_data, {
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

    deleteMember(ctx, { id }) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/member/${id}`)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },

    editLevelMember(ctx, dataSend) {
      return new Promise((resolve, reject) => {
        axios
          .put(`/member/level/${dataSend.id}`, dataSend)
          .then((response) => {
            return resolve(response);
          })
          .catch((error) => reject(error));
      });
    },
  },
};
