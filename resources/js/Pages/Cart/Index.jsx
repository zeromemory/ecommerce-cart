import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ cartItems }) {
    const [updatingId, setUpdatingId] = useState(null);

    const handleUpdateQuantity = (cartItemId, newQuantity) => {
        if (newQuantity < 1) return;

        setUpdatingId(cartItemId);
        router.patch(`/cart/${cartItemId}`, {
            quantity: newQuantity,
        }, {
            preserveScroll: true,
            onFinish: () => setUpdatingId(null),
        });
    };

    const handleRemove = (cartItemId) => {
        if (confirm('Are you sure you want to remove this item?')) {
            router.delete(`/cart/${cartItemId}`, {
                preserveScroll: true,
            });
        }
    };

    const handleCheckout = () => {
        router.post('/checkout', {}, {
            preserveScroll: true,
        });
    };

    const calculateTotal = () => {
        return cartItems.reduce((total, item) => {
            return total + (parseFloat(item.product.price) * item.quantity);
        }, 0).toFixed(2);
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Shopping Cart
                    </h2>
                    <a
                        href="/products"
                        className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        Continue Shopping
                    </a>
                </div>
            }
        >
            <Head title="Cart" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {cartItems.length === 0 ? (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 text-center text-gray-600">
                                <p className="text-lg">Your cart is empty</p>
                                <a
                                    href="/products"
                                    className="inline-block mt-4 px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                                >
                                    Start Shopping
                                </a>
                            </div>
                        </div>
                    ) : (
                        <div className="space-y-6">
                            {cartItems.map((item) => (
                                <div
                                    key={item.id}
                                    className="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                                >
                                    <div className="p-6 flex items-center justify-between">
                                        <div className="flex-1">
                                            <h3 className="text-lg font-semibold text-gray-900">
                                                {item.product.name}
                                            </h3>
                                            <p className="text-gray-600 text-sm mt-1">
                                                {item.product.description}
                                            </p>
                                            <p className="text-lg font-semibold text-gray-900 mt-2">
                                                ${item.product.price} each
                                            </p>
                                        </div>
                                        <div className="flex items-center gap-4">
                                            <div className="flex items-center gap-2">
                                                <button
                                                    onClick={() => handleUpdateQuantity(item.id, item.quantity - 1)}
                                                    disabled={item.quantity <= 1 || updatingId === item.id}
                                                    className="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                                                >
                                                    -
                                                </button>
                                                <span className="w-12 text-center font-semibold">
                                                    {item.quantity}
                                                </span>
                                                <button
                                                    onClick={() => handleUpdateQuantity(item.id, item.quantity + 1)}
                                                    disabled={item.quantity >= item.product.stock_quantity || updatingId === item.id}
                                                    className="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
                                                >
                                                    +
                                                </button>
                                            </div>
                                            <div className="text-right min-w-[100px]">
                                                <p className="text-lg font-bold text-gray-900">
                                                    ${(parseFloat(item.product.price) * item.quantity).toFixed(2)}
                                                </p>
                                            </div>
                                            <button
                                                onClick={() => handleRemove(item.id)}
                                                className="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}

                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <div className="flex justify-between items-center mb-4">
                                        <span className="text-xl font-semibold text-gray-900">
                                            Total:
                                        </span>
                                        <span className="text-2xl font-bold text-gray-900">
                                            ${calculateTotal()}
                                        </span>
                                    </div>
                                    <button
                                        onClick={handleCheckout}
                                        className="w-full px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold"
                                    >
                                        Proceed to Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
