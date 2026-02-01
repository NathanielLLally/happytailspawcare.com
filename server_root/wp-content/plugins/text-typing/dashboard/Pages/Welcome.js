import Overview from "../../../bpl-tools/Admin/Overview/Overview";
import { Button } from "../../../bpl-tools/Components";

const Welcome = (props) => {
  const { isPremium } = props;

  return (
    <>
      <Overview {...props}>
        {!isPremium && (
          <Button href="#pricing" variant="secondary">
            Buy Now
          </Button>
        )}
      </Overview>
    </>
  );
};
export default Welcome;