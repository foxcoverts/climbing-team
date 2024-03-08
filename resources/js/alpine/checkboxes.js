export default (all_values = []) => ({
    values: [],
    indeterminate(el) {
        if (all_values.every((value) => this.values.includes(value))) {
            el.checked = true;
            el.indeterminate = false;
        } else {
            el.checked = false;
            el.indeterminate = this.values.length > 0;
        }
    },
    selectAll(event) {
        if (event.target.checked) {
            this.values = all_values;
        } else {
            this.values = [];
        }
    },
});
