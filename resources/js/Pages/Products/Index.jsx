import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Index({ products }) {
    const [quantities, setQuantities] = useState({});

    const handleAddToCart = (productId) => {
        const quantity = quantities[productId] || 1;
        router.post('/cart/add', {
            product_id: productId,
            quantity: quantity,
        }, {
            preserveScroll: true,
            onSuccess: () => {
                setQuantities({ ...quantities, [productId]: 1 });
            }
        });
    };

    const handleQuantityChange = (productId, value) => {
        const newValue = Math.max(1, parseInt(value) || 1);
        setQuantities({ ...quantities, [productId]: newValue });
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Products
                    </h2>
                    <a
                        href="/cart"
                        className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    >
                        View Cart
                    </a>
                </div>
            }
        >
            <Head title="Products" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {products.map((product) => (
                            <div
                                key={product.id}
                                className="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                            >
                                <div className="p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                        {product.name}
                                    </h3>
                                    <p className="text-gray-600 text-sm mb-4">
                                        {product.description}
                                    </p>
                                    <div className="flex justify-between items-center mb-4">
                                        <span className="text-2xl font-bold text-gray-900">
                                            ${product.price}
                                        </span>
                                        <span className={`text-sm ${product.stock_quantity <= 5 ? 'text-red-600 font-semibold' : 'text-gray-600'}`}>
                                            {product.stock_quantity > 0
                                                ? `Stock: ${product.stock_quantity}`
                                                : 'Out of Stock'
                                            }
                                        </span>
                                    </div>
                                    <div className="flex gap-2">
                                        <input
                                            type="number"
                                            min="1"
                                            max={product.stock_quantity}
                                            value={quantities[product.id] || 1}
                                            onChange={(e) => handleQuantityChange(product.id, e.target.value)}
                                            className="w-20 px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                            disabled={product.stock_quantity === 0}
                                        />
                                        <button
                                            onClick={() => handleAddToCart(product.id)}
                                            disabled={product.stock_quantity === 0}
                                            className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
                                        >
                                            {product.stock_quantity === 0 ? 'Out of Stock' : 'Add to Cart'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
