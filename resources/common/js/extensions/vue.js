export default function extend(Vue) {
    Vue.prototype.$r = (name, parameters, absolute) => {
        return window.Router.route(name, parameters, absolute);
    };
}
