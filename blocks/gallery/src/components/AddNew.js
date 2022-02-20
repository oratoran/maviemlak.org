import { Dashicon } from "@wordpress/components";
import styled from "styled-components";

const AddNewStyles = styled.button`
  width: 240px;
  height: 240px;
  border: 2px solid #ddd;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  cursor: pointer;
  text-align: center;
  margin: 10px;

  &:hover {
    border-color: #222;

    .dashicons {
      color: #222;
    }
  }

  .dashicons {
    width: 32px;
    height: 32px;
    color: #ddd;
    font-size: 32px;
  }
`;

export const AddNew = ({ onClick }) => {
  return (
    <AddNewStyles onClick={onClick} type="button">
      <Dashicon icon="plus" />
    </AddNewStyles>
  );
};
