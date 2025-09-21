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

    // Update cart preview
    updateCartPreview();
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

function removeFromCart(productId) {
    const formData = new FormData();
    formData.append("produto_id", productId);
    formData.append(
        "_token",
        document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content")
    );

    fetch("/carrinho/remove", {
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
                    data.message || "Erro ao remover produto do carrinho"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showCartError(
                "Erro ao remover produto do carrinho. Tente novamente."
            );
        });
}

function updateCartPreview() {
    fetch("/carrinho/preview", {
        method: "GET",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            const cartPreviewElement = document.getElementById("cart-preview");
            if (cartPreviewElement) {
                if (data.total_items > 0) {
                    // Show first few items
                    const items = Object.values(data.items);
                    const previewItems = items.slice(0, 3);
                    let previewHtml = '';
                    
                    previewItems.forEach(item => {
                        previewHtml += `<div class="flex justify-between items-center py-1">
                            <span class="text-xs truncate">${item.nome}</span>
                            <span class="text-xs font-medium">${item.quantidade}x</span>
                        </div>`;
                    });
                    
                    if (items.length > 3) {
                        previewHtml += `<div class="text-xs text-gray-500 mt-1">+${items.length - 3} mais</div>`;
                    }
                    
                    previewHtml += `<div class="border-t border-gray-200 mt-2 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">Total:</span>
                            <span class="text-sm font-bold">${data.formatted_total}</span>
                        </div>
                    </div>`;
                    
                    cartPreviewElement.innerHTML = previewHtml;
                } else {
                    cartPreviewElement.textContent = "Nenhum item";
                }
            }
        })
        .catch((error) => {
            console.error("Error getting cart preview:", error);
        });
}

// Initialize cart count on page load
document.addEventListener("DOMContentLoaded", function () {
    getCartCount();
    updateCartPreview();
});
