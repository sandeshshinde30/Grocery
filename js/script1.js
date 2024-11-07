let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>{
    searchForm.classList.toggle('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

let shoppingCart = document.querySelector('.shopping-cart');

document.querySelector('#cart-btn').onclick = () =>{
    shoppingCart.classList.toggle('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

let loginForm = document.querySelector('.login-form');

document.querySelector('#login-btn').onclick = () =>{
    loginForm.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () =>{
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
}

window.onscroll = () =>{
    searchForm.classList.remove('active');
    shoppingCart.classList.remove('active');
    loginForm.classList.remove('active');
    navbar.classList.remove('active');
}

  var swiper = new Swiper(".product-slider", {
      loop:true,
      spaceBetween: 20,
      autoplay: {
          delay: 7500,
          disableOnInteraction: false,
      },
      centeredSlides: true,
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        768: {
          slidesPerView: 2,
        },
        1020: {
          slidesPerView: 3,
        },
      },
  });

var swiper = new Swiper(".review-slider", {
    loop:true,
    spaceBetween: 20,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    centeredSlides: true,
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      1020: {
        slidesPerView: 3,
      },
    },
});


document.addEventListener("DOMContentLoaded", () => {
  checkUserSession();
});

function checkUserSession() {
  fetch('profile.php', {
      method: 'GET',
      credentials: 'include', // Include cookies with the request
  })
  .then(response => response.json())
  .then(data => {
      if (data.isLoggedIn) {
          displayUserForm(data.username);
      }
  })
  .catch(error => console.error('Error fetching profile:', error));
}

function displayUserForm(username) {
  const loginForm = document.querySelector('.login-form');
  loginForm.innerHTML = `
      <h3>Welcome, ${username}</h3>
      <p>You are already logged in.</p>
      <a href="logout.php" class="btn">Logout</a>
  `;
}


function redirectToLogin() {
  window.location.href = "login.html";
}






// Function to generate star ratings
function generateStars(rating) {
  let stars = "";
  for (let i = 1; i <= 5; i++) {
      if (i <= rating) {
          stars += `<i class="fas fa-star"></i>`;
      } else if (i - rating < 1) {
          stars += `<i class="fas fa-star-half-alt"></i>`;
      } else {
          stars += `<i class="far fa-star"></i>`;
      }
  }
  return stars;
}

// Fetch product data from the PHP endpoint
// Fetch product data from the PHP endpoint
async function fetchProducts() {
    try {
        const response = await fetch('products.php');
        if (!response.ok) throw new Error('Failed to fetch products');

        const products = await response.json();
        console.log('Products fetched:', products); // Debug log
        renderProducts(products);
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

// Function to render products dynamically
function renderProducts(products) {
    const productContainer = document.getElementById("product-container");
    let productHTML = "";

    products.forEach((product) => {
        productHTML += `
            <div class="swiper-slide box">
                <img src="${product.image}" alt="${product.name}">
                <h3>${product.name}</h3>
                <div class="price">$${product.price}</div>
                <div class="stars">
                    ${generateStars(product.rating)}
                </div>
                <a href="#" class="btn add-to-cart" data-name="${product.name}" data-image="${product.image}" data-price="${product.price}">add to cart</a>
            </div>
        `;
    });

    productContainer.innerHTML = productHTML;

    // Add event listeners to all "Add to Cart" buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            const name = this.getAttribute('data-name');
            const image = this.getAttribute('data-image');
            const price = this.getAttribute('data-price');

            // Call function to add item to cart
            addToCart(name, image, price);
        });
    });
}

// Function to add item to the cart
async function addToCart(name, image, price) {
    const email = getCookie('email'); // Fetch email from cookies
    const requestData = {
        name: name,
        image: image,
        price: price,
        email: email,
        quantity: 1 // Assuming a quantity of 1 for simplicity
    };

    console.log(requestData);

    try {
        const response = await fetch('addCart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
        });

        const result = await response.json();
        console.log("Item added to cart:", result);

        // Optionally, show a success message or update the UI
        if (result.success) {
            alert("Item added to cart!");
            location.reload();
        } else {
            alert(result.error || "Failed to add item to cart.");
        }
    } catch (error) {
        console.error('Error adding item to cart:', error);
    }
}






// Function to get cookie value by name
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

// Call the function to fetch and render products on page load
document.addEventListener("DOMContentLoaded", fetchProducts);



 // Fetch categories from the PHP endpoint
 async function fetchCategories() {
  try {
      const response = await fetch('categories.php');
      if (!response.ok) throw new Error('Failed to fetch categories');

      const categories = await response.json();
      console.log('Categories fetched:', categories); // Debug log
      renderCategories(categories);
  } catch (error) {
      console.error('Error fetching categories:', error);
  }
}

function renderCategories(categories) {
  const container = document.getElementById('categories-container');

  if (!container) {
      console.error('Container not found!');
      return;
  }

  console.log('Rendering categories...'); // Debug log

  container.innerHTML = ''; // Clear previous content
  categories.forEach(category => {
      const categoryHTML = `
          <div class="box">
              <img src="${category.image}" alt="${category.name}">
              <h3>${category.name}</h3>
              <p>${category.discount}</p>
              <a href="#" class="btn">Shop Now</a>
          </div>
      `;
      container.insertAdjacentHTML('beforeend', categoryHTML);
  });
}

// Fetch and display categories on page load
window.addEventListener('DOMContentLoaded', fetchCategories);




// Function to generate star ratings
function generateStars(rating) {
  let stars = "";
  for (let i = 1; i <= 5; i++) {
      if (i <= rating) {
          stars += `<i class="fas fa-star"></i>`;
      } else if (i - rating < 1) {
          stars += `<i class="fas fa-star-half-alt"></i>`;
      } else {
          stars += `<i class="far fa-star"></i>`;
      }
  }
  return stars;
}

// Fetch review data from the PHP endpoint
async function fetchReviews() {
  try {
      const response = await fetch('reviews.php');
      if (!response.ok) throw new Error('Failed to fetch reviews');

      const reviews = await response.json();
      console.log('Reviews fetched:', reviews); // Debug log
      renderReviews(reviews);
  } catch (error) {
      console.error('Error fetching reviews:', error);
  }
}

// Function to render reviews dynamically
function renderReviews(reviews) {
  const reviewContainer = document.getElementById("reviews-container");
  let reviewHTML = "";

  reviews.forEach((review) => {
      reviewHTML += `
          <div class="swiper-slide box">
              <img src="${review.image}" alt="${review.name}">
              <p>${review.text}</p>
              <h3>${review.name}</h3>
              <div class="stars">
                  ${generateStars(review.rating)}
              </div>
          </div>
      `;
  });

  reviewContainer.innerHTML = reviewHTML;
}

// Call the function to fetch and render reviews on page load
document.addEventListener("DOMContentLoaded", fetchReviews);









document.addEventListener("DOMContentLoaded", function() {
  fetch('cart.php')
      .then(response => response.json())
      .then(data => {
          const shoppingCart = document.getElementById('shoppingCart');
          const totalPriceElement = document.getElementById('totalPrice');
          let total = 0; // Initialize total

          console.log("Data : ", data);

          // Create a container for cart items
          const itemsContainer = document.createElement('div');
          itemsContainer.className = 'items-container'; // Optional: style for the items
          shoppingCart.appendChild(itemsContainer); // Append it to shoppingCart

          // Clear the items container before adding new items
          itemsContainer.innerHTML = '';

          // Check if there is an error message
          if (data.error) {
              const message = document.createElement('div');
              message.className = 'error-message'; // Optional: style for the error message
              message.textContent = data.error; // Display the error message
              itemsContainer.appendChild(message);
          } else {
              // Loop through the cart items and create HTML elements
              data.forEach(item => {
                  const box = document.createElement('div');
                  box.classList.add('box');

                  const trashIcon = document.createElement('i');
                  trashIcon.classList.add('fas', 'fa-trash');
                  trashIcon.dataset.name = item.name; // Store the item's name in a data attribute
                  trashIcon.addEventListener('click', deleteItem); // Add click event listener
                  box.appendChild(trashIcon);

                  const img = document.createElement('img');
                  img.src = item.image;
                  img.alt = item.name;
                  box.appendChild(img);

                  const content = document.createElement('div');
                  content.classList.add('content');

                  const title = document.createElement('h3');
                  title.textContent = item.name;
                  content.appendChild(title);

                  const price = document.createElement('span');
                  price.classList.add('price');
                  price.textContent = `$${item.price}/-`;
                  content.appendChild(price);

                  const quantity = document.createElement('span');
                  quantity.classList.add('quantity');
                  quantity.textContent = `qty: ${item.quantity}`;
                  content.appendChild(quantity);

                  box.appendChild(content);
                  itemsContainer.appendChild(box); // Append to the itemsContainer

                  // Update the total price
                  total += parseFloat(item.price) * item.quantity;
              });

              // Update total price display
              totalPriceElement.textContent = `Total: $${total.toFixed(2)}-`;
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
});

// Function to delete cart item using product name
async function deleteItem(event) {
  const itemName = this.dataset.name; // Get the item name from the data attribute

  if (!itemName) {
      console.error('Item name not found');
      return;
  }

  try {
      const response = await fetch('deleteCart.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ name: itemName }) // Send the item name to delete
      });

      const result = await response.json();
      console.log("Delete response:", result);

      if (result.success) {
          // If successful, reload the cart to update UI
          location.reload(); // Or fetch the cart again to update UI
      } else {
          alert(result.error || "Failed to delete item from cart.");
      }
  } catch (error) {
      console.error('Error deleting item:', error);
  }
}
