import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "../components/App.vue";
import router from "@/router/index";

import Button from "@common/Button.vue";
import Icon from "@common/Icon.vue";
import Input from "@common/Input.vue";
import ButtonIcon from "@common/ButtonIcon.vue";
import Card from "@common/Card.vue";
import Modal from "@common/Modal.vue";
import Accordion from "@common/Accordion.vue";
import Switch from "@common/Switch.vue";
import Snackbar from "@common/Snackbar.vue";
import Badge from "@common/Badge.vue";

import "../css/app.css";

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.component("Button", Button);
app.component("Icon", Icon);
app.component("Input", Input);
app.component("ButtonIcon", ButtonIcon);
app.component("Card", Card);
app.component("Modal", Modal);
app.component("Accordion", Accordion);
app.component("Switch", Switch);
app.component("Snackbar", Snackbar);
app.component("Badge", Badge);

app.mount("#app");
