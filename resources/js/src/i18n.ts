import { createI18n } from "vue-i18n";
import messages from "@intlify/vite-plugin-vue-i18n/messages";

const i18n = createI18n({
    legacy: false,
    allowComposition: true,
    locale: "pt-BR",
    globalInjection: true,
    fallbackLocale: "en",
    messages: {
        ...messages,
        "pt-BR": {
            // Adicione as traduções para o pt-BR aqui
            datatable: {
                search: "Pesquisar",
                columns: "Colunas",
                perPage: "Itens por página",
                noDataAvailable: "Nenhum dado disponível",
                showing: "Mostrando",
                to: "até",
                of: "de",
                entries: "registros",
                all: "Todos",
            },
        },
    },
});

export default i18n;
