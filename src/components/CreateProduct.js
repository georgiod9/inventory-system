import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

export default function CreateProduct() {
    const navigate = useNavigate();

    const [inputs, setInputs] = useState([]);

    const handleSubmit = (event) => {
        event.preventDefault();
        //console.log("SUBMITTING: ", event);
        

        axios.post('http://localhost:80/api/product/save', inputs)
            .then(function(response) {
                console.log(response.data);
                navigate('/');
            })
    }

    const handleChange = (event) => {
        //console.log("Event Name: ", event.target.name)
        //console.log("Event Value: ", event.target.value)
        const name = event.target.name;
        const value = event.target.value;
        setInputs(values => ({...values, [name]: value}));
        console.log(inputs)
    }

    const [productType, setProductType] = useState('no_selection')
    const handleFormAppearance = (event) => {
        const getOption = event.target.value;
        setProductType(getOption)
    }

    return (
        <div>
            <h1>Add Product</h1>

            <div className="pageContent">
                <form id="#product_form" onSubmit={handleSubmit}>
                    <table cellSpacing="10">
                        <tbody>
                            <tr>
                                <th>
                                    <label>SKU: </label>
                                </th>
                                <td>
                                    <input type="text" name="sku" onChange={handleChange} />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Name: </label>
                                </th>
                                <td>
                                    <input type="text" name="name" onChange={handleChange} />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Price(USD): </label>
                                </th>
                                <td>
                                    <input type="text" name="price" onChange={handleChange} />
                                </td>
                            </tr>
                            <tr>
                                <td colSpan="2" align="right">
                                    <button >Save</button>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label>Product Type: </label>
                                </th>
                                <td>
                                    <select onChange={(e) => (handleFormAppearance(e))} id="myselect" defaultValue="select">
                                        <option value="no_selection" >Select Product Type</option>
                                        <option value="form_dvd">DVD</option>
                                        <option value="form_furniture">Furniture</option>
                                        <option value="form_book">Book</option>
                                    </select>
                                </td>
                            </tr>





                            {productType === 'form_dvd' && (
                                <tr>
                                    <th>
                                        <label>Size (MB):</label>
                                    </th>
                                    <td>
                                        <input type="text" name="size" onChange={handleChange} />
                                    </td>
                                </tr>
                            )}

                            {productType === 'form_furniture' && (
                                <>

                                    <tr>
                                        <th>
                                            <label>Height (cm):</label>
                                        </th>
                                        <td>
                                            <input type="text" name="height" onChange={handleChange} />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Width (cm):</label>
                                        </th>
                                        <td>
                                            <input type="text" name="width" onChange={handleChange} />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Length (cm):</label>
                                        </th>
                                        <td>
                                            <input type="text" name="length" onChange={handleChange} />
                                        </td>
                                    </tr>
                                </>


                            )}

                            {productType === 'form_book' && (
                                <tr>
                                    <th>
                                        <label>Weight (kg):</label>
                                    </th>
                                    <td>
                                        <input type="text" name="weight" onChange={handleChange} />
                                    </td>
                                </tr>
                            )}

                        </tbody>
                    </table>
                    {productType === 'no_selection' && (
                        <div>
                            <p>Choose product type to enter additional details.</p>
                        </div>
                    )}
                    {productType === 'form_dvd' && (
                        <div>
                            <p>Disc size required*</p>
                        </div>
                    )}
                    {productType === 'form_furniture' && (
                        <div>
                            <p>Furniture dimensions required as HxWxL*</p>
                        </div>
                    )}
                    {productType === 'form_book' && (
                        <div>
                            <p>Book weight required*</p>
                        </div>
                    )}
                </form>

               













            </div>


        </div>
    );
}