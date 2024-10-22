document.addEventListener('DOMContentLoaded', function () {
    const category = document.getElementById('category');
    const subcategory = document.getElementById('subcategory');

    const subcategories = {
        fruitsVegetables: ['Fresh Fruits', 'Fresh Vegetables'],
        dairy: ['Milk', 'cheese', 'Yogurt', 'Butter','eggs'],
        pantryStaples: ['Rice and Grains', 'Pasta and Noodles', 'Oils', 'Sauces and Condiments'],
        beverages: ['Water', 'Juices', 'Soft Drinks', 'Coffee and Tea'],
        snacksSweets: ['Chips and Crackers', 'Chocolates and Candies', 'Nuts and Seeds'],
        personalCare: ['Toiletries', 'Skincare', 'Haircare', 'Oral care'],
        household: ['Cleaning Supplies', 'Paper Products (Toilet Paper, Paper Towels)', 'Laundry Supplies', 'Dishwashing Supplies'],
        babyProducts: ['Baby Food', 'Diapers', 'Baby Wipes', 'baby care']

    };

    category.addEventListener('change', function () {
        const selectedCategory = category.value;

        while (subcategory.firstChild) {
            subcategory.removeChild(subcategory.firstChild);
        }

        if (subcategories[selectedCategory]) {
            subcategories[selectedCategory].forEach(function (subcat) {
                const option = document.createElement('option');
                option.value = subcat.toLowerCase();
                option.textContent = subcat;
                subcategory.appendChild(option);
            });
        } else {
            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select a subcategory';
            subcategory.appendChild(defaultOption);
        }
    });
});
