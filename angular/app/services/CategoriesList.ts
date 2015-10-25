export class CategoriesList {
    private categories = ['Food/home', 'Transport', 'Salary', 'Others'];

    get() {
        return this.categories;
    }
    add(value: string) {
        this.categories.push(value);
    }
}
