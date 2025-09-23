import { defineStore } from "pinia";

export type Student = {
  id: string;
  name: string;
  email: string;
  cpf: string;
  created_at: string;
};

type ApiStudentsResponse = {
  data: Student[];
  current_page: number;
  total: number;
};

type FetchStudentsParams = {
  pageNumber: number;
  queryParams?: string;
};

export const useStudentsStore = defineStore("students", {
  state: () => ({
    students: [] as Student[],
    total: 0,
    current_page: 1,
    page: 1,
    loading: false,
  }),
  actions: {
    async fetchStudents({ pageNumber, queryParams }: FetchStudentsParams) {
      try {
        this.loading = true;
        const config = useRuntimeConfig();

        let url = `${config.public.apiBase}/students?page=${pageNumber}`;
        if (queryParams) url += `&${queryParams}`;

        const response = await $fetch<ApiStudentsResponse>(url);

        this.students = response.data;
        this.total = response.total;
        this.current_page = response.current_page;
      } finally {
        this.loading = false;
      }
    },
  },
});
