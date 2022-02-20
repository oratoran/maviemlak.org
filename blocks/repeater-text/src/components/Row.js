import { Dashicon } from "@wordpress/components";
import styled from "styled-components";

const RowContainer = styled.div`
    display: flex;
    align-items: center;
    width: 100%;

    .item {
        padding: 0 12px;
        margin: 12px 0;
    }

    .item.value {
        flex: 1;

        input {
            width: 100%;
        }
    }

    button {
        background: transparent;
        border: none;
    }
`;

export const Row = ({ dataKey, dataValue, onChange, addNew }) => {
    return (
        <RowContainer>
            <div className="item">
                <input
                    type="text"
                    placeholder="Key"
                    value={dataKey}
                    onChange={(e) => onChange("key", e.currentTarget.value)}
                />
            </div>
            <div className="item value">
                <input
                    type="text"
                    placeholder="Value"
                    value={dataValue}
                    onChange={(e) => onChange("value", e.currentTarget.value)}
                />
            </div>
            <button onClick={addNew}>
                <Dashicon icon="plus" />
                Add new Row
            </button>
        </RowContainer>
    );
};
