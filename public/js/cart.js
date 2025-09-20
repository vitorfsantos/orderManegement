// Cart functionality with AJAX

function addToCart(productId, quantity = 1) {
    const formData = new FormData();
    formData.append("produto_id", productId);
    formData.append("quantidade", quantity);
    formData.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );

    fetch("/carrinho/add", {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showCartSuccess(data.message);
                updateCartCount(data.total_items);
            } else {
                showCartError(
                    data.message || "Erro ao adicionar produto ao carrinho"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showCartError(
                "Erro ao adicionar produto ao carrinho. Tente novamente."
            );
        });
}

function updateCartCount(totalItems) {
    // Update desktop cart count
    const cartCountElement = document.getElementById("cart-count");
    if (cartCountElement) {
        cartCountElement.textContent = totalItems;
    }

    // Update header cart count
    const cartCountHeaderElement = document.getElementById("cart-count-header");
    if (cartCountHeaderElement) {
        cartCountHeaderElement.textContent = totalItems;
    }

    // Show/hide desktop cart count badge
    const cartBadge = document.getElementById("cart-badge");
    if (cartBadge) {
        if (totalItems > 0) {
            cartBadge.classList.remove("hidden");
        } else {
            cartBadge.classList.add("hidden");
        }
    }

    // Show/hide header cart count badge
    const cartBadgeHeader = document.getElementById("cart-badge-header");
    if (cartBadgeHeader) {
        if (totalItems > 0) {
            cartBadgeHeader.classList.remove("hidden");
        } else {
            cartBadgeHeader.classList.add("hidden");
        }
    }
}

function getCartCount() {
    fetch("/carrinho/count", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            updateCartCount(data.total_items);
        })
        .catch((error) => {
            console.error("Error getting cart count:", error);
        });
}

function showCartSuccess(message) {
    // Create success notification
    const notification = document.createElement("div");
    notification.className =
        "fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50";
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showCartError(message) {
    // Create error notification
    const notification = document.createElement("div");
    notification.className =
        "fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50";
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Initialize cart count on page load
document.addEventListener("DOMContentLoaded", function () {
    getCartCount();
});
