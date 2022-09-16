import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";

export default function CreateProduct() {
    const navigate = useNavigate();

    const [inputs, setInputs] = useState([]);

    const handleSubmit = (event) => {
        event.preventDefault();
        //console.log("Event is: ", event.target);

        axios.post('http://localhost:80/api/product/save', [inputs, attributes, attributeName])
            .then(function(response) {
                console.log(response.data);
                navigate('/');
            })
    }

    const handleChange = (event) => {
        //console.log("Event Name: ", event.target.name)
        //console.log("Event Value: ", event.target.value)
        //console.log("handleChange event: ", event);

        const name = event.target.name;
        const value = event.target.value;
        setInputs(values => ({...values, [name]: value}));
        console.log(inputs)
    }

    const [attributes, setAttributes] = useState([]);
    const [attributeName, setAttributeName] = useState([]);

    const handleAttributes = (event) => {
        //console.log("Attribute event: ", event);
        //console.log("Attribute count: ", attributeCount);
        const attrName = event.target.name;
        const attrValue = event.target.value;
        const attrId = event.target.id;
        console.log("attrID: ",attrId);

        console.log("attrName: ", attrName);
        console.log("attrvalue: ", attrValue);

        setAttributeName(values => ({...values, [attrId]: attrName}));
        setAttributes(values => ({...values, [attrId]: attrValue}));
        console.log("Attribute Name: ", attributeName);
        console.log("Attributes: ", attributes);
    }

    const [productType, setProductType] = useState('no_selection')
    const handleFormAppearance = (event) => {
        const getOption = event.target.value;
        setProductType(getOption)
        setInputs(values => ({...values, ["type"]: getOption}));
        console.log(inputs);
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
                                        <option value="dvd">DVD</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="book">Book</option>
                                    </select>
                                </td>
                            </tr>


                            {productType === 'dvd' && (
                                <tr>
                                    <th>
                                        <label>Size (MB):</label>
                                    </th>
                                    <td>
                                        <input id="0" type="text" name="size" onChange={handleAttributes} />
                                    </td>
                                </tr>
                            )}

                            {productType === 'furniture' && (
                                <>

                                    <tr>
                                        <th>
                                            <label>Height (cm):</label>
                                        </th>
                                        <td>
                                            <input id="0" type="text" name="height" onChange={handleAttributes} />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Width (cm):</label>
                                        </th>
                                        <td>
                                            <input id="1" type="text" name="width" onChange={handleAttributes} />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <label>Length (cm):</label>
                                        </th>
                                        <td>
                                            <input id="2" type="text" name="length" onChange={handleAttributes} />
                                        </td>
                                    </tr>
                                </>


                            )}

                            {productType === 'book' && (
                                <tr>
                                    <th>
                                        <label>Weight (kg):</label>
                                    </th>
                                    <td>
                                        <input id="0" type="text" name="weight" onChange={handleAttributes} />
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
                    {productType === 'dvd' && (
                        <div>
                            <p>Disc size required*</p>
                        </div>
                    )}
                    {productType === 'furniture' && (
                        <div>
                            <p>Furniture dimensions required as HxWxL*</p>
                        </div>
                    )}
                    {productType === 'book' && (
                        <div>
                            <p>Book weight required*</p>
                        </div>
                    )}
                </form>

               













            </div>


        </div>
    );
}