import { render, useEffect, useState, useRef } from "@wordpress/element";
import { Row } from "./components/Row";

const App = () => {
    const [items, setItems] = useState(
        _REPEATER_TEXT_DATA || [
            {
                key: "",
                value: "",
            },
        ]
    );

    const list = items.map((item, i) => (
        <Row
            addNew={() => setItems((p) => [...p, { key: "", value: "" }])}
            removeRow={() => setItems((p) => p.filter((_, index) => index !== i))}
            dataKey={item.key || ""}
            dataValue={item.value || ""}
            showDelete={items.length > 1}
            showAddNew={i === items.length - 1}
            onChange={(key, value) => {
                setItems((p) => {
                    return p.map((c, ci) => {
                        if (ci === i) {
                            return {
                                ...c,
                                [key]: value,
                            };
                        }

                        return c;
                    });
                });
            }}
        />
    ));

    return (
        <div
            style={{
                display: "flex",
                alignItems: "center",
                flexWrap: "wrap",
            }}
        >
            {list}

            <input
                type="hidden"
                name="_repeater_text_data"
                value={JSON.stringify(items)}
            />
        </div>
    );
};

render(<App />, document.getElementById("custom-metabox-repeatertext"));
