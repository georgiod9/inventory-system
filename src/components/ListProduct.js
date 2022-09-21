import { useState } from "react";
import { useEffect } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import ItemCard from './ItemCard';

export default function ListProduct() {
    const [productList, setProductList] = useState([]);

    useEffect(() => {
        fetchProducts();
        //console.log("fetched from server: ", productList);
    }, []);

    function fetchProducts() {
        axios.get('http://localhost:80/api/product/')
        .then(function(response) {
            console.log("Logged from axios: ", response.data);
            setProductList(response.data);
        })
        .catch(function (error) {
            console.log(error);
        })
    }

    return (
        <div>
            <h1>Product List</h1>
            <ItemCard products={productList}/>
        </div>
        
    );
  }