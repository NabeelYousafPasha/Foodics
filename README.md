## Foodics - Problem Statement

In a system that has three main models; Product, Ingredient, and Order.

A Burger (Product) may have several ingredients:
```phpt
- 150g Beef
- 30g Cheese
- 20g Onion
```

The system keeps the stock of each of these ingredients stored in the database. 
You can use the following levels for seeding the database:

```phpt
- 20kg Beef 
- 5kg Cheese
- 1kg Onion
```

When a customer makes an order that includes a Burger. The system needs to update the 
stock of each of the ingredients so, it reflects the amounts consumed. Also when any of the ingredients stock level reaches 50%, the system should send an 
email message to alert the merchant they need to buy more of this ingredient. 

### Requirements:

<b>First</b>, Write a controller action that:

1. Accepts the order details from the request payload.
2. Persists the Order in the database.
3. Updates the stock of the ingredients.

<b>Second</b>, ensure that en email is sent once the level of any of the ingredients reach 
below 50%. Only a single email should be sent, further consumption of the same 
ingredient below 50% shouldn't trigger an email.

<b>Finally</b>, write several test cases that assert the order was correctly stored and the 
stock was correctly updated. 

The incoming `payload` may look like this:
```phpt
{
    "products": [
        {
            "product_id": 1,
            "quantity": 2,
        }
    ]
}
```
<hr>

## My Proposed Solution


### ERD

It could be encanced more, but instead of over engineering, that's it for challanging test. Moreover ERD has been updated and danger_level column from ingredients has been updated to threshold_level. 

![https://github.com/NabeelYousafPasha/Foodics](./Foodics-ERD-10-03-2023.png?raw=true "https://github.com/NabeelYousafPasha/Foodics")

### Tests

`php artisan test --filter OrderTest`

![https://github.com/NabeelYousafPasha/Foodics](./Tests.png?raw=true "https://github.com/NabeelYousafPasha/Foodics")
