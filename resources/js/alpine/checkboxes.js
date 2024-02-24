export default (all_values = []) => ({
    values: [],
    all: false,
    indeterminate() {
        if (all_values.every((value) => this.values.includes(value))) {
            this.all = true;
            return false;
        } else {
            this.all = false;
            return this.values.length > 0;
        }
    },
    init() {
        this.$watch("values", () => this.indeterminate());
        this.$watch("all", (all) => {
            if (all) {
                this.values = all_values;
            } else if (
                /* only unselect all if all are selected */
                all_values.every((value) => this.values.includes(value))
            ) {
                this.values = [];
            }
        });
    },
});
